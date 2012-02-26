<?php

interface sfPlopSlotTypeInterface
{
  public function getRequestParameters();
  public function getContentClasses();
  public function getDefaultValue();
  public function getDefaultOptions();
  public function isContentEditable();
  public function isContentOptionable();
  public function getFields($slot);
  public function getFieldHelps();
  public function getValidators($slot);
  public function getSlotValue($slot, $settings);
}
