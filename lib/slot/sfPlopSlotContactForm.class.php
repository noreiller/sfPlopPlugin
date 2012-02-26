<?php

class sfPlopSlotContactForm extends sfPlopSlotStandard
{
  public function isContentEditable() { return false; }

  public function isContentOptionable() { return true; }
  
  public function getFields($slot) 
  {
    return array(
      'title' => new sfWidgetFormInputText(array(
        'label' => 'Contact'
      )),
      'contact' => new sfWidgetFormPlopChoiceContact(array(
        'label' => 'Contact'
      ))
    );
  }

  public function getValidators($slot) 
  {
    return array(
      'title' => new sfValidatorString(array(
        'required' => false
      )),
      'contact' => new sfValidatorPlopChoiceContact(array(
        'required' => false
      ))
    );
  }
  
  protected function getParams($slot, $settings)
  {
    return array(
      'title' => $slot->getOption('title', null, $settings['culture']),
      'form' => new sfPlopPublicContactForm(null, array(
        'contact' => $slot->getOption('contact', null, $settings['culture'])
      ))
    );
  }
}
