<?php

/**
 * Description of sfValidatorPlopChoiceTheme
 *
 * @author Aurélien MANCA <aurelien.manca@gmail.com>
 */
class sfValidatorPlopChoiceSubTheme extends sfValidatorChoice
{
  public function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->setOption('choices', array());
  }

  public function getChoices()
  {
    $themes = sfPlop::get('sf_plop_loaded_themes');
    $theme = sfPlop::get('sf_plop_theme');

    $choices = array();

    if (isset($themes[$theme]['subthemes']) && is_array($themes[$theme]['subthemes']))
    {
      $choices = array_keys($themes[$theme]['subthemes']);
    }

    return $choices;
  }
}

?>
