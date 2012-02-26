<?php
/**
 * Displays a select box with the contacts.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage widget
 * @author     ##AUTHOR_NAME##
 */
class sfWidgetFormPlopChoiceContact extends sfWidgetFormPropelChoice
{
  public function configure($options = array(), $attributes = array()) {
    parent::configure($options, $attributes);

    $this->setOption('model', 'sfGuardUserProfile');
    $this->setOption('method', 'getNameWithRole');
    $this->setOption('criteria', sfGuardUserProfileQuery::create()->filterByIsPublic(true));
    $this->setOption('multiple', true);
    $this->setOption('expanded', true);
  }  
}
