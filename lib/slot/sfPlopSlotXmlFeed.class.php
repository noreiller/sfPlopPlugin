<?php

class sfPlopSlotXmlFeed extends sfPlopSlotStandard
{
  public function isContentOptionable() { return true; }

  public function isContentEditable() { return false; }

  public function getFields($slot) 
  {
    return array(
      'url' => new sfWidgetFormInputText(array(
        'label' => 'Url'
      )),
      'limit' => new sfWidgetFormInputText(array(
        'label' => 'Number of elements',
        'default' => 5,
      )),
      'truncate_type' => new sfWidgetFormChoice(array(
        'choices' => $this->getFeedElements(),
        'default' => 'element_description',
        'label' => 'Element to truncate',
        'expanded' => true
      )),
      'truncate_text' => new sfWidgetFormInputText(array(
        'label' => 'Number of caracters',
        'default' => 150,
      )),
      'displays' => new sfWidgetFormChoice(array(
        'choices' => $this->getFeedElements(),
        'label' => 'Elements to display',
        'default' => array_keys($this->getFeedElements()),
        'multiple' => true,
        'expanded' => true
      )),
    );
  }

  public function getValidators($slot) 
  {
    return array(
      'url' => new sfValidatorUrl(array(
        'required' => false
      )),
      'limit' => new sfValidatorInteger(array(
        'required' => false
      )),
      'truncate_type' => new sfValidatorChoice(array(
        'choices' => array_keys($this->getFeedElements())
      )),
      'truncate_text' => new sfValidatorInteger(array(
        'required' => false
      )),
      'displays' => new sfValidatorChoice(array(
        'choices' => array_keys($this->getFeedElements()),
        'multiple' => true,
        'required' => false
      )),
    );
  }

  protected function getFeedElements() 
  {
    return array(
      'title' => 'title',
      'element_title' => 'title of the element',
      'element_description' => 'description of the element',
      'element_date' => 'date of the element',
    );
  }

  protected function getParams($slot, $settings)
  {
    $limit = $slot->getOption('limit', 0);
    $url = $slot->getOption('url');

    if ($url)
    {
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      $data = curl_exec($ch);
      curl_close($ch);
    }

    if (isset($data) && $data !== false)
    {
      $doc = new SimpleXmlElement($data, LIBXML_NOCDATA);
      if (isset($doc->channel))
      {
        $feed = $this->parseRSS($doc, $limit);
      }
      elseif (isset($doc->entry))
      {
        $feed = $this->parseAtom($doc, $limit);
      }
    }

    $displays = $slot->getOption('displays', array());
    if (count($displays) == 0)
      $displays = array_keys($this->getFeedElements());

    return array(
      'feed' => isset($feed) ? $feed : false,
      'displays' => $displays,
      'truncate_text' => $slot->getOption('truncate_text'),
      'truncate_type' => $slot->getOption('truncate_type')
    );
  }

  protected function parseRSS($xml, $limit)
  {
    $feed = array(
      'title' => $xml->channel->title,
      'count' => count($xml->channel->item),
      'items' => array()
    );

    for ($i = 0; $i < $feed['count']; $i++)
    {
      $feed['items'] []= array(
        'date'  => date('Y-m-d h:i:s A', strtotime($xml->channel->item[$i]->pubDate)),
        'url'  => $xml->channel->item[$i]->link,
        'title'  => $xml->channel->item[$i]->title,
        'description' => $xml->channel->item[$i]->description
      );
     
      if ($i == $limit) 
        break;
    }

    return $feed;
  }

  protected function parseAtom($xml, $limit)
  {
    $feed = array(
      'title' => $xml->author->name,
      'count' => count($xml->entry),
      'items' => array()
    );

    for ($i = 0; $i < $feed['count']; $i++)
    {
      $urlAtt = $xml->entry->link[$i]->attributes();
      $feed['items'] []= array(
        'date'  => date('Y-m-d h:i:s A', strtotime($xml->entry[$i]->published)),
        'url'  => $urlAtt['href'],
        'title'  => $xml->entry[$i]->title,
        'description' => strip_tags($xml->entry[$i]->content)
      );
     
      if ($i == $limit) 
        break;
    }

    return $feed;
  }
}
