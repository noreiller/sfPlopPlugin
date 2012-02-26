<?php
/**
 * It renders an input text HTML tag and a javascript tag to trigger the display
 * of a modal box with a color map.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage widget
 * @author     ##AUTHOR_NAME##
 */
class sfWidgetFormInputColor extends sfWidgetFormInput
{
  public function  render($name, $value = null, $attributes = array(), $errors = array()) 
  {
    $id = $this->generateId($name);

    if (isset($attributes['class']))
      $attributes['class'] .= ' w-theme-editor-color';
    else 
      $attributes['class'] = ' w-theme-editor-color'; 

    return
      content_tag('span',
        parent::render($name, $value, $attributes, $errors)
        . content_tag('span',
          image_tag('/sfPlopPlugin/vendor/famfamfam/silk/shading.png'),
          array(
            'class' => 'w-clickable w-theme-editor-transparent',
            'data-target'  => $id
        ))
        . content_tag('span',
          image_tag('/sfPlopPlugin/vendor/famfamfam/silk/delete.png'),
          array(
            'class' => 'w-clickable w-theme-editor-delete',
            'data-target'  => $id
        ))
      );
  }
}
