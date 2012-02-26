<?php

/**
 * Description of sfValidatorPlopChoiceTheme
 *
 * @author Aurélien MANCA <aurelien.manca@gmail.com>
 */
class sfValidatorPlopChoiceTheme extends sfValidatorChoice
{
  public function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->setOption('choices', array());
  }

  public function getChoices()
  {
    $choices = sfPlop::getAllThemes();

    foreach ($choices as $key => $choice)
    {
      if (is_array($choice))
      {
        unset($choices[$key]);
        $choices += $choice;
      }
    }

    return array_keys($choices);
  }
}

?>
