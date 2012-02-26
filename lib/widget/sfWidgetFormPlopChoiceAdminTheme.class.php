<?php
/**
 * Displays a select box with the available admin themes.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage widget
 * @author     ##AUTHOR_NAME##
 */
class sfWidgetFormPlopChoiceAdminTheme extends sfWidgetFormChoice
{
  public function configure($options = array(), $attributes = array())
  {
    $this->addOption('add_empty', false);

    parent::configure($options, $attributes);

    $this->setOption('choices', array());
  }

  public function getChoices()
  {
    $choices = array();
    $themes = sfPlop::get('sf_plop_loaded_admin_themes');

    foreach($themes as $theme)
      $choices [$theme['name']]= $theme['description'];


    if ($this->options['add_empty'] === true)
      $choices = array('' => '') + $choices;

    return $choices;
  }
}
