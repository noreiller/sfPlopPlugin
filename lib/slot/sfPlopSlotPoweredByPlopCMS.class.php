<?php

/**
 * Slot to display the Plop CMS license link.
 */
class sfPlopSlotPoweredByPlopCMS extends sfPlopSlotStandard
{
  public function isContentEditable() { return false; }

  public function getDefaultValue()
  {
    return '<a href="http://www.plop-cms.com" class="w-powered-button"
     title="' . __('Powered by %s', array('%s' => 'Plop CMS')) . '">' . __('Powered by %s', array(
      '%s' => content_tag('em', 'Plop CMS'))) . '</a>';
  }

  public function getSlotValue($slot, $settings)
  {
    return $this->getDefaultValue();
  }
}
