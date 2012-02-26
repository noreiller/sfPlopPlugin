<?php

/**
 * sfPlopSlot form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopSlotCopyForm extends BasesfPlopSlotForm
{
  public function configure()
  {
    parent::configure();

    unset(
      $this['layout'],
      $this['template'],
      $this['is_editable'],
      $this['sortable_rank'],
      $this['created_at'],
      $this['updated_at']
    );

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('plopAdmin');

    $this->widgetSchema['page_id'] = new sfWidgetFormChoice(array(
      'choices' => sfPlopPagePeer::getPagesWithLevel(true)
    ));
    $this->validatorSchema['page_id'] = new sfValidatorChoice(array(
      'choices' => array_keys(sfPlopPagePeer::getPagesWithLevel(true)),
      'required' => false
    ));

    if ($this->getOption('page_id'))
      $this->widgetSchema['page_id']->setDefault($this->getOption('page_id'));


    $this->widgetSchema['is_published']->setLabel('Is published ?');
  }

  protected function doSave($con = null)
  {
    //parent::doSave($con);

    $values = $this->values;
    $slot_ref = $this->getOption('slot_ref');
    $slot = new sfPlopSlot();

    $slot_ref->copyInto($slot, true);
    $slot->swapPage($values['page_id']);
    $slot->setIsPublished($values['is_published']);
    $slot->save();

    $this->object = $slot;
  }
}
