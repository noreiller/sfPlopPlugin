<?php

class sfPlopSlotXmlFeed extends sfPlopSlotStandard
{
  public function getFields($settings) 
  {
    return array(
      'url' => new sfWidgetFormInputText(array(
        'label' => 'Url'
      )),
      'limit' => new sfWidgetFormInputText(array(
        'label' => 'Number of elements'
      )),
      'truncate_type' => new sfWidgetFormChoice(array(
        'choices' => $this->getFeedElements(),
        'default' => 'element_description',
        'label' => 'Element to truncate',
        'expanded' => true
      )),
      'truncate_text' => new sfWidgetFormInputText(array(
        'label' => 'Number of caracters'
      )),
      'displays' => new sfWidgetFormChoice(array(
        'choices' => $this->getFeedElements(),
        'label' => 'Elements to display',
        'multiple' => true,
        'expanded' => true
      )),
    );
  }

  public function getValidators($slot) 
  {
    return array(
      'url  ' => new sfValidatorUrl(array(
        'required' => false
      )),
      'limit' => new sfValidatorInteger(array(
        'required' => false
      )),
      'truncate_type' => new sfValidatorChoice(array(
        'choices' => array_keys($this->getFeedElements())
      )),
      'truncate_text' => new sfValidatorInteger(array(
        'required' => false
      )),
      'displays' => new sfValidatorChoice(array(
        'choices' => array_keys($this->getFeedElements()),
        'multiple' => true,
        'required' => false
      )),
    );
  }

  public function getFeedElements() 
  {
    return array(
      'title' => 'title',
      'element_title' => 'title of the element',
      'element_description' => 'description of the element',
      'element_date' => 'date of the element',
    );
  }
}
