<?php

/**
 * Description of sfValidatorPlopChoiceAdminTheme
 *
 * @author Aurélien MANCA <aurelien.manca@gmail.com>
 */
class sfValidatorPlopChoiceAdminTheme extends sfValidatorChoice
{
  public function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->setOption('choices', array());
  }

  public function getChoices()
  {
    $choices = array();
    $themes = sfPlop::get('sf_plop_loaded_admin_themes');

    foreach($themes as $theme)
      $choices [$theme['name']]= $theme['description'];

    return array_keys($choices);
  }
}

?>
