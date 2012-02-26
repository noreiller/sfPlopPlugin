<?php
/**
 * Displays a select box with the page slugs.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage widget
 * @author     ##AUTHOR_NAME##
 */
class sfWidgetFormPlopChoicePageSlug extends sfWidgetFormChoice
{
  public function configure($options = array(), $attributes = array())
  {
    $this->addOption('add_empty', false);

    parent::configure($options, $attributes);

    $this->setOption('choices', array());
  }

  public function getChoices()
  {
    $slugs = sfPlopPagePeer::getPageSlugsWithLevel();

    if (isset($this->options['add_empty']))
      $slugs = array('' => '') + $slugs;

    return $slugs;
  }
}
