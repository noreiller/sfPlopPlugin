<?php

class sfPlopSlotRegisterForm extends sfPlopSlotNone
{
  public function getParams($slot, $settings)
  {
    $form = new sfPlopGuardProfileRegisterForm(null, array(
      'user_culture' => $settings['culture'],
      'culture' => $settings['culture']
    ));

    return array(
      'form' => $form
    );
  }
}
