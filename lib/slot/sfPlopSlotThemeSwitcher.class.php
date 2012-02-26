<?php

/**
 * Slot to display a theme switcher widget.
 */
class sfPlopSlotThemeSwitcher extends sfPlopSlotNone
{
  public function getRequestParameters()
  {
    return array(
      'theme'
    );
  }

  protected function getParams($slot, $settings)
  {
    if (isset($settings['request_parameters']) && isset($settings['request_parameters']['theme']))
      $theme = $settings['request_parameters']['theme']['theme'];
    else
      $theme = sfPlop::get('sf_plop_theme');

    return array(
      'default_theme' => sfPlop::get('sf_plop_theme'),
      'theme' => $theme,
      'form' => new sfPlopPublicThemeSwitcherForm(null, array(
        'default_theme' => sfPlop::get('sf_plop_theme'),
        'theme' => $theme
      ))
    );
  }
}
