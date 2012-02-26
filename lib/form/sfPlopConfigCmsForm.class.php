<?php

/**
 * sfPlopConfigCms form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopConfigCmsForm extends sfPlopConfigForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'sf_plop_cms_enabled_slot_templates' => new sfWidgetFormChoice(array(
        'multiple' => true,
        'expanded' => true,
        'choices' => self::getSlots(),
        'default' => sfPlop::getEnabledSlots(),
        'label' => 'Enabled slot templates'
      ))
    ));

    $this->setValidators(array(
      'sf_plop_cms_enabled_slot_templates' => new sfValidatorChoice(array(
        'multiple' => true,
        'choices' => array_keys(self::getSlots())
      ))
    ));

    parent::configure();
  }

  public function getSlots() {
    $config_slots = sfPlop::get('sf_plop_cms_slot_templates', true);
    $loaded_slots = sfPlop::get('sf_plop_cms_loaded_slot_templates');
    if(!$loaded_slots)
      $loaded_slots = array();

    return array_merge(
      $config_slots,
      $loaded_slots
    );
  }
}
