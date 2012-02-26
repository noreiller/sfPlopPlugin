<?php

/**
 * PPH slot class, to be used by the sfSimpleCMSHelper.
 * The slot must contain valid HTML with embedded PHP tags
 * Example:
 * <code>
 * // If the slot value is
 * Hello, <?php echo 'world' ?>!
 * // Then the getSlotValue() method will return
 * Hello, world!
 * </code>
 */
class sfPlopSlotCode extends sfPlopSlotStandard
{
  public function isContentEditable() { return false; }
  
  public function isContentOptionable() { return true; }
  
  public function getSlotValue($slot, $settings)
  {
    ob_start();
    eval('?>' . $slot->getValue() . '<?php ');
    
    return ob_get_clean();
  }
}
