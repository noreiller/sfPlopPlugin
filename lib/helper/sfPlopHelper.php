<?php

/**
 * Remove whitespaces from HTML
 * @param String $s
 * @see http://www.blog.highub.com/regular-expression/php-regex-regular-expression/php-regex-remove-whitespace-from-html/
 */
function remove_whitespaces_from_html($s = '')
{
  // Clean the inside of the tags
  $s = preg_replace("/[ \t\n\r]+/", " ", $s);
  // Clean the outside of the tags
  $s = preg_replace('/([\t\s\n]*)(<.*>)([\t\s\n]*)/', '$2', $s);
  // Remove empty fieldset
  $s = preg_replace('/(<fieldset><\/fieldset>)/', '', $s);
  $s = preg_replace('/(<fieldset> <\/fieldset>)/', '', $s);
  
  return $s;
}

/**
 * Set the theme
 * @param Mixed $css String or array
 */
function use_plop_theme($class = '', $theme = null)
{
  if (!$theme)
    $theme = sfPlop::get('sf_plop_theme');

  if (preg_match('/\s/', $theme) !== false)
  {
    $subthemes = explode(' ', $theme);
    $theme = array_shift($subthemes);
    $class .= (' ' . implode(' ', $subthemes));
  }

  $themes = sfPlop::get('sf_plop_loaded_themes');

  if ($theme != false  && is_string($theme) && isset($themes[$theme]))
  {
    if (isset($themes[$theme]['css'])) use_plop_stylesheet($themes[$theme]['css']);
    if (isset($themes[$theme]['js'])) use_plop_javascript($themes[$theme]['js']);
  }
  else
  {
    $theme = '';
  }

  if (has_slot('sf_plop_theme') && $theme == sfPlop::get('sf_plop_theme'))
    slot('sf_plop_theme', get_slot('sf_plop_theme') . $theme . ' ' . $class);
  else
    slot('sf_plop_theme', $theme . ' ' . $class);
}

/**
 * Add one or several stylesheet file(s) to the page
 * @param Mixed $css String or array
 */
function use_plop_stylesheet($css)
{
  $version = sfPlop::getVersion();
  $suffix = 'v=' . $version;

  if (is_array($css))
    foreach($css as $f)
      use_stylesheet($f . (strpos($f, '?') ? '&' : '?') . $suffix, '', array('media' => 'all'));
  else
    use_stylesheet($css . (strpos($css, '?') ? '&' : '?') . $suffix, '', array('media' => 'all'));
}

/**
 * Add one or several JavaScript file(s) to the page
 * @param Mixed $j String or array
 */
function use_plop_javascript($js)
{
  if (is_array($js))
    foreach($js as $f)
      use_javascript($f);
  else
    use_javascript($js);
}

/**
 * Output the image tag of the country flag according to a ISO 2-alpha country code
 *
 * @param string $country 2 letter ISO code of the country
 * @param Array $attributes HTML attributes
 * @param Boolean $strict Don't use replacement such as "england" with "en"
 * @return string
 */
function image_flag($country, $attributes = array(), $strict = false)
{
  if ($strict === true)
  {
    if ($country == "en")
      $country = "england";
  }
  else
  {
    if ($country == "en")
      $country = "gb";
  }

  $path = sfPlop::get('sf_plop_country_flags_path');

  return image_tag($path . strtolower($country), $attributes);
}

/**
 * Output an HTML string which aims to set a CSS clearfix.
 * @return String
 */
function clear()
{
  return '<div class="clearfix"></div>';
}

/**
 * Output an HTML5 tag entity name if the application uses HTML5, otherwise
 * it returns a standard block DIV element.
 * @return String
 */
function html5Tag($element)
{
  return sfPlop::get('sf_plop_use_html5') ? $element : 'div';
}

/**
 * Renders a HTML tag with classes to indicate the status of a widget.
 * @param Boolean $status
 * @param String $type
 * @param String $title
 * @param Array $attributes HTML attributes
 * @return String
 */
function widgetIndicator($status = false, $type = '', $title = '', $attributes = array())
{
  $attributes['title'] = $title;
  $attributes['class'] = 'w w-help w-' . $type . ' w-' . ($status === false ? 'off' : 'on');

  return content_tag('span', $title, $attributes);
}

?>