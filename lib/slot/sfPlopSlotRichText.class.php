<?php

/**
 * Rich text slot class, to be used by the sfPlopCMSHelper.
 * The slot must contain valid HTML code.
 * Example:
 * <code>
 * // If the slot value is
 * Hello, <b>world</b>!
 * // Then the getSlotValue() method will return
 * Hello, <b>world</b>!
 * </code>
 */
class sfPlopSlotRichText extends sfPlopSlotStandard
{
  public function getContentClasses() { return 'RichText'; }

  public function getDefaultValue()
  {
    return '
      <h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit</h2>
      <p>Nunc sit amet <a href="/">facilisis magna</a>. Suspendisse id leo nibh. Nunc <strong>rhoncus</strong> sapien sed ipsum tempus ut sodales eros lobortis. Nulla facilisi. Donec scelerisque nisi id nunc vehicula tristique in quis ligula. Duis quis eros ut magna feugiat euismod id euismod urna.</p>
      <hr />
      <ul><li>Donec vel magna augue</li><li>Mauris in auctor nunc</li></ul>
    ';
  }

  public function getFields($slot)
  {
    return array(
      'value' => new sfWidgetFormPlopRichText(array(
        'label' => 'Content'
      ))
    );
  }

  public function getSlotValue($slot, $settings)
  {
    return htmlspecialchars_decode($slot->getValue($settings['culture']), ENT_QUOTES);
  }
}
