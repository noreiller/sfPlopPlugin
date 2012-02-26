<?php
/**
 * Displays a select box with the page slugs.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage widget
 * @author     ##AUTHOR_NAME##
 */
class sfWidgetFormPlopChoiceSubTheme extends sfWidgetFormChoice
{
  public function configure($options = array(), $attributes = array())
  {
    $this->addOption('add_empty', false);

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
      $choices = $themes[$theme]['subthemes'];

      if ($this->options['add_empty'] === true)
        $choices = array('' => '') + $choices;
    }

    return $choices;
  }
}
