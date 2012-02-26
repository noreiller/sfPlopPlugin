<?php
// @TODO : enable external links

class sfPlopSlotRedirection extends sfPlopSlotStandard
{

  public function getFields($settings) 
  {
    return array(
      'value' => new sfWidgetFormPlopChoicePageSlug(array(
        'label' => 'Page'
      ))
    );
  }

  public function getValidators($slot) 
  {
    return array(
      'value' => new sfValidatorPlopChoicePageSlug()
    );
  }

  public function getSlotValue($slot, $settings) 
  {
    if (!$settings['is_edition'] && $slot->getValue() && ($slot->getValue != ''))
    {
      sfContext::getInstance()->getController()->redirect(sfPlopTools::urlForPage($slot->getValue, '', $slot->getCulture()));
    }
		elseif (!$settings['is_edition'])
    {
      if ($ssub_page = $slot->getPage()->retrieveFirstChild()) 
      {
        $slug = $ssub_page->getSlug();
        sfContext::getInstance()->getController()->redirect(sfPlopTools::urlForPage($slug, '', $slot->getCulture()));
      }
		}
  }
}
