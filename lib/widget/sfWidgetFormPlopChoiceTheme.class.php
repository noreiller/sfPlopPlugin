<?php
/**
 * Displays a select box with the available themes.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage widget
 * @author     ##AUTHOR_NAME##
 */
class sfWidgetFormPlopChoiceTheme extends sfWidgetFormChoice
{
  public function configure($options = array(), $attributes = array())
  {
    $this->addOption('add_empty', false);

    parent::configure($options, $attributes);

    $this->setOption('choices', array());
  }

  public function getChoices()
  {
    $choices = sfPlop::getAllThemes();

    if ($this->options['add_empty'] === true)
      $choices = array('' => '') + $choices;

    return $choices;
  }
}
