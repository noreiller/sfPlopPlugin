<?php

class sfPlopSlotStandard implements sfPlopSlotTypeInterface
{
  public function getRequestParameters() { return array(); }

  public function getContentClasses() { return ''; }

  public function getDefaultValue() { return null; }

  public function getDefaultOptions() { return null; }

  public function isContentEditable() { return true; }

  public function isContentOptionable() { return false; }

  public function getFields($slot)
  {
    return array(
      'value' => new sfWidgetFormTextarea(array(
        'label' => 'Content'
      ), array(
        'rows' => 4,
        'cols' => 15
      ))
    );
  }

  public function getFieldHelps() { return array(); }

  public function getValidators($slot)
  {
    return array(
      'value' => new sfValidatorString(array(
        'required' => false
      ))
    );
  }

  public function getSlotValue($slot, $settings)
  {
    return get_partial($slot->getTemplate(), array(
      'slot' => $slot,
      'settings' => $settings,
    ) + $this->getParams($slot, $settings));
  }

  protected function getParams($slot, $settings)
  {
    return array();
  }
}
