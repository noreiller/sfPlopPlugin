<?php

/**
 * sfPlopSlot form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopSlotForm extends BasesfPlopSlotForm
{
  public function configure()
  {
    parent::configure();

    unset(
      $this['is_published'],
      $this['is_editable'],
      $this['sortable_rank'],
      $this['created_at'],
      $this['updated_at']
    );

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('plopAdmin');

    $this->widgetSchema['page_id'] = new sfWidgetFormInputHidden();
    if ($this->getOption('page_id'))
      $this->widgetSchema['page_id']->setDefault($this->getOption('page_id'));

    $this->widgetSchema['layout'] = new sfWidgetFormChoice(array(
      'choices' => sfPlop::get('sf_plop_slot_layouts')
    ));
    $this->validatorSchema['layout'] = new sfValidatorChoice(array(
      'choices' => array_keys(sfPlop::get('sf_plop_slot_layouts')),
      'required' => false
    ));

    $templates = sfPlop::getSafePluginSlots();
    if ($this->getOption('unset_area') === true && isset($templates['Area']))
      unset($templates['Area']);

    $this->widgetSchema['template'] = new sfWidgetFormChoice(array(
      'choices' => $templates
    ));
    $this->validatorSchema['template'] = new sfValidatorChoice(array(
      'choices' => array_keys($templates),
      'required' => false
    ));

    // post validator for template which can't be area if the page or the template page has already one
    // remove  Area when page contains already one

  }
}
