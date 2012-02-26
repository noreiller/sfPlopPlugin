<?php
/**
 * Validates media tags.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage widget
 * @author     ##AUTHOR_NAME##
 */
class sfValidatorPlopChoiceContact extends sfValidatorPropelChoice
{
  public function configure($options = array(), $attributes = array()) {
    parent::configure($options, $attributes);

    $this->setOption('model', 'sfGuardUserProfile');
    $this->setOption('criteria', sfGuardUserProfileQuery::create()->filterByIsPublic(true));
    $this->setOption('multiple', true);
  }
}
