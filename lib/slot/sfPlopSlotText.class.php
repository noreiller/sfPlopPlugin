<?php

/**
 * Simple text slot class, to be used by the sfPlopCMSHelper.
 * The slot must contain text, and is simply formatted in HTML
 * By transforming carriage returns into <br/> and URLs into hyperlinks.
 * Example:
 * <code>
 * // If the slot value is
 * Hello, world.
 * My address is http://www.example.com
 * // Then the getSlotValue() method will return
 * Hello, world.<br />
 * My address is <a href="http://www.example.com">http://www.example.com</a>
 * </code>
 */
class sfPlopSlotText extends sfPlopSlotStandard
{
  public function isContentEditable() { return false; }
  
  public function isContentOptionable() { return true; }
  
  public function getSlotValue($slot, $settings)
  {
    return simple_format_text(auto_link_text(strip_tags($slot->getValue($settings['culture']))));
  }
}
