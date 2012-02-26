<?php

/**
 * Slot to display a gallery hosted on the web.
 */
class sfPlopSlotDistantGallery extends sfPlopSlotStandard
{
  public function isContentEditable() { return false; }
  public function isContentOptionable() { return true; }

  public function getFields($slot)
  {
    return array(
      'feed_url' => new sfWidgetFormInputText(array(
        'label' => 'Feed url'
      )),
      'feed_type' => new sfWidgetFormChoice(array(
        'label' => 'Feed type',
        'choices' => $this->getFeedTypes()
      )),
      'legend' => new sfWidgetFormChoice(array(
        'choices' => $this->getLegendChoice(),
        'label' => 'Legend'
      )),
      'pagination' => new sfWidgetFormChoice(array(
        'choices' => $this->getPaginationChoice(),
        'label' => 'Pagination'
      )),
      'use_prev_next_buttons' => new sfWidgetFormInputCheckbox(array(
        'value_attribute_value' => true,
        'label' => 'Use previous and next buttons ?'
      )),
      'autoplay' => new sfWidgetFormInputCheckbox(array(
        'value_attribute_value' => true,
        'label' => 'Autoplay ?'
      ))
    );
  }

  public function getValidators($slot) {
    return array(
      'feed_url' => new sfValidatorUrl(array(
        'label' => 'Feed url'
      )),
      'feed_type' => new sfValidatorChoice(array(
        'label' => 'Feed type',
        'choices' => array_keys($this->getFeedTypes())
      )),
      'legend' => new sfValidatorChoice(array(
        'required' => false,
        'choices' => array_keys($this->getLegendChoice())
      )),
      'pagination' => new sfValidatorChoice(array(
        'required' => false,
        'choices' => array_keys($this->getPaginationChoice())
      )),
      'use_prev_next_buttons' => new sfValidatorBoolean(),
      'autoplay' => new sfValidatorBoolean()
    );
  }

  protected function getParams($slot, $settings)
  {
    $return = array('gallery' => null);
    $feed_url = $slot->getOption('feed_url', null, $settings['culture']);
    $feed_type = $slot->getOption('feed_type', null, $settings['culture']);
    if ($feed_type == 'flickr')
      $feed_url = $this->setFlickrUrl($feed_url, $settings);
    elseif ($feed_type == 'picasa')
      $feed_url = $this->setPicasaUrl($feed_url, $settings);
    else
      $feed_url = null;

    if ($feed_url && $feed_type)
    {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $feed_url);
      curl_setopt($curl, CURLOPT_HEADER, 0);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $result = curl_exec($curl);
      curl_close($curl);
    }
    else
      $result = false;

    if ($result !== false)
    {
      $return['feed_type'] = $feed_type;
      $return['pagination'] = $slot->getOption('pagination', 'numbers', $settings['culture']);
      $return['legend'] = $slot->getOption('legend', 'hidden', $settings['culture']);
      $return['use_prev_next_buttons'] = $slot->getOption('use_prev_next_buttons', false, $settings['culture']);
      $return['autoplay'] = $slot->getOption('autoplay', false, $settings['culture']);
      if ($feed_type == 'flickr')
        $return['gallery'] = $this->parseFlickrFeed($result, $settings['culture']);
      elseif ($feed_type == 'picasa')
        $return['gallery'] = $this->parsePicasaFeed($result, $settings['culture']);
    }

    return $return;
  }

  /**
   * Construct Flickr url feed in according to the settings
   * @param String $url
   * @param Array $settings
   * @return String
   */
  protected function setFlickrUrl($url, $settings)
  {
    $url .= strpos($url, '?') === false ? '?' : '&';
    $url .= 'format=php_serial&';
    $lang = strtolower($settings['culture']);
    if (strpos($lang, '_') === true)
      str_replace ('_', '-', $lang);
    elseif (strpos($lang, '-') === false)
      $lang = $lang . '-' . $lang;
    switch ($lang)
    {
      case 'en-en':
        $lang = 'en-us';
        break;
      case 'es-es':
        $lang = 'es-us';
        break;
      case 'ko-ko':
        $lang = 'ko-kr';
        break;
      case 'pt-pt':
        $lang = 'pt-br';
        break;
      case 'zh-zh':
        $lang = 'zh-hk';
        break;
    }
    $url .= ('lang=' . $lang);

    return $url;
  }

  /**
   * Construct a normalized array in according to the Flickr results
   * @param Array $result
   * @param Array $settings
   * @return Array
   */
  protected function parseFlickrFeed($result, $settings)
  {
    $result = unserialize($result);

		$elements = array();
		foreach ($result['items'] as $element)
		{
			$elements []= array(
        'published' => $element['date_taken'],
        'updated' => $element['date_taken'],
        'title' => $element['title'],
        'content' => image_tag($element['photo_url']),
        'author' => $element['author_name'],
      );
		}

    return array(
      'elements' => $elements,
      'count' => count($elements),
      'keys' => array(
        'author' => 'author',
        'content' => 'content'
      )
    );
  }

  /**
   * Construct Picasa url feed in according to the settings
   * @param String $url
   * @param Array $settings
   * @return String
   */
  protected function setPicasaUrl($url, $settings)
  {
    $url .= strpos($url, '?') === false ? '?' : '&';
    $url .= 'alt=json&max-results=20&';
    $lang = strtolower($settings['culture']);
    $url .= ('hl=' . $lang);

    return $url;
  }

  /**
   * Construct a normalized array in according to the Picasa results
   * @param Array $result
   * @param Array $settings
   * @return Array
   */
  protected function parsePicasaFeed($result, $settings)
  {
    $result = json_decode($result);

    $elements = array();
    foreach($result->feed->entry as $element)
    {

      $authors = array();
      foreach ($element->author as $author)
      {
        $variables = get_object_vars($author->name);
        $keys = array_keys($variables);
        $authors []= $variables[$keys[0]];
      }

      $elements []= array(
        'published' => $element->published,
        'updated' => $element->updated,
        'title' => $element->title,
        'content' => image_tag($element->content->src),
        'author' => is_array($authors)
          ? implode(',', $authors)
          : $element->author[0],
      );
    }

    return array(
      'elements' => $elements,
      'count' => count($elements),
      'keys' => array(
        'author' => 'author',
        'content' => 'content'
      )
    );
  }

  protected function getPaginationChoice()
  {
    return array(
      'hidden' => 'Hidden',
      'numbers' => 'Numbers'
//      'thumbs' => 'Thumbs',
//      'thumbs-slider' => 'Thumbs in slider'
    );
  }

  protected function getFeedTypes()
  {
    return array(
      'flickr' => 'Flickr',
      'picasa' => 'Picasa'
    );
  }

  protected function getLegendChoice()
  {
    return array(
      'hidden' => 'Hidden',
//      'description' => 'Description',
//      'copyright' => 'Copyright',
      'author' => 'Author'
    );
  }
}
