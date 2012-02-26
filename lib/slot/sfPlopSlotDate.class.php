<?php

class sfPlopSlotDate extends sfPlopSlotStandard
{
  public function getFields($settings)
  {
    return array(
      'displays' => new sfWidgetFormChoice(array(
        'choices' => array(
          'date' => 'date',
          'time' => 'time',
        ),
        'label' => 'Elements to display',
        'multiple' => true,
        'expanded' => true
      )),
    );
  }

  public function getValidators($slot)
  {
    return array(
      'displays' => new sfValidatorChoice(array(
        'required' => false,
        'choices' => array(
          'date',
          'time'
        ),
        'multiple' => true
      )),
    );
  }

  protected function getParams($slot, $settings)
  {
    $displays = $slot->getOption('displays', array(), $settings['culture']);
    $use_clock = in_array('time', $displays) ? true : false;
    $use_date = (in_array('date', $displays) || !$use_clock) ? true : false;

    return array(
      'displays' => $displays,
      'use_clock' => $use_clock,
      'use_date' => $use_date
    );
  }
}
