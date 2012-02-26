<?php

/**
 * sfPlopConfigPluginSlots form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopConfigPluginSlotsForm extends sfPlopConfigForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'sf_plop_enabled_slots' => new sfWidgetFormChoice(array(
        'multiple' => true,
        'expanded' => true,
        'choices' => sfPlop::getSafePluginSlots(true),
        'default' => sfPlop::get('sf_plop_enabled_slots'),
        'label' => 'Enabled slots'
      ))
    ));

    $this->setValidators(array(
      'sf_plop_enabled_slots' => new sfValidatorChoice(array(
        'required' => false,
        'multiple' => true,
        'choices' => array_keys(sfPlop::getSafePluginSlots(true))
      ))
    ));

    parent::configure();
  }
}
