<?php

class sfPlopSlotLoginForm extends sfPlopSlotNone
{
  protected function getParams($slot, $settings)
  {
    $class = sfConfig::get('app_sf_guard_plugin_signin_form', 'sfGuardFormSignin');
    $form = new $class();

    return array(
      'form' => new $class()
    );
  }
}
