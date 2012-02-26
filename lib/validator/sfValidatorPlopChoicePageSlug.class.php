<?php
/**
 * Validates a page slug.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage widget
 * @author     ##AUTHOR_NAME##
 */
class sfValidatorPlopChoicePageSlug extends sfValidatorPropelChoice
{
  public function configure($options = array(), $attributes = array()) {
    parent::configure($options, $attributes);
    $this->setOption('model', 'sfPlopPage');
    $this->setOption('column', 'slug');
  }
}
