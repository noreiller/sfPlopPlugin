<?php
/**
 * It renders a select box tag.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage widget
 * @author     ##AUTHOR_NAME##
 */
class sfWidgetFormCssProperty extends sfWidgetFormChoice
{
  public function configure($options = array(), $attributes = array()) {
    parent::configure($options, $attributes);

    $this->addRequiredOption('property');
    $this->addOption('add_empty', true);
    $this->setOption('choices', array());
  }

  public function getChoices() {
    $choices = sfConfig::get('sf_plop_css_' . $this->getOption('property'));
    if ($this->getOption('add_empty') && is_array($choices))
      $choices = array('' => '') + $choices;

    $this->setOption('choices', $choices);

    return parent::getChoices();
  }

  public function render($name, $value = null, $attributes = array(), $errors = array()) {
    if (!$value)
      $value = $this->getAttribute('value');

    return parent::render($name, $value, $attributes, $errors);
  }

}