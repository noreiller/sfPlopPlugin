<?php

class sfPlopSlotPageTitle extends sfPlopSlotNone
{
  public function getContentClasses() { return 'RichText'; }
  
  public function getSlotValue($slot, $settings) 
  {
    return content_tag('h1', $settings['page']->getTitle());
  }
}
