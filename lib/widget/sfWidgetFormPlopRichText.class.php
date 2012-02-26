<?php

class sfWidgetFormPlopRichText extends sfWidgetFormTextarea
{
  /**
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (isset($attributes['class']))
      $attributes['class'] .= ' w-richtext';
    else
      $attributes['class'] = 'w-richtext';

    return parent::render($name, $value, $attributes, $errors);
  }
}
