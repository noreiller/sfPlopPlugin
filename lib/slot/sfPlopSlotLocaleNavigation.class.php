<?php

class sfPlopSlotLocaleNavigation extends sfPlopSlotStandard
{
  public function isContentOptionable() { return true; }

  public function isContentEditable() { return false; }

  public function getDefaultValue() { return array(); }

  public function getFields($slot)
  {
    return array(
      'title' => new sfWidgetFormInputText(array(
        'label' => 'Title'
      )),
      'hide_current' => new sfWidgetFormInputCheckbox(array(
        'value_attribute_value' => true,
        'label' => 'Hide current translation ?'
      )),
      'use_dropdown_menu' => new sfWidgetFormInputCheckbox(array(
        'value_attribute_value' => true,
        'label' => 'Use dropdown menu ?'
      )),
      'displays' => new sfWidgetFormChoice(array(
        'choices' => sfPlop::get('sf_plop_menu_items'),
        'label' => 'Elements to display',
        'multiple' => true,
        'expanded' => true
      )),
      'relative_page' => new sfWidgetFormPlopChoicePageSlug(array(
        'label' => 'Page',
        'add_empty' => true
      ))
    );
  }

  public function getFieldHelps()
  {
    return array(
      'title' => 'Only used with dropdown menu. If not filled, the current language will be used.'
    );
  }
  public function getValidators($slot)
  {
    return array(
      'title' => new sfValidatorString(array(
        'required' => false,
      )),
      'hide_current' => new sfValidatorBoolean(),
      'use_dropdown_menu' => new sfValidatorBoolean(),
      'displays' => new sfValidatorChoice(array(
        'required' => false,
        'choices' => array_keys(sfPlop::get('sf_plop_menu_items')),
        'multiple' => true
      )),
      'relative_page' => new sfValidatorPlopChoicePageSlug(array(
        'required' => false
      ))
    );
  }

  protected function getParams($slot, $settings)
  {
    $relative_slug = $slot->getOption('relative_page', '', $settings['culture']);
    $localizations = sfPlop::get('sf_plop_cultures');
    $displays = $slot->getOption('displays', array(), $settings['culture']);
    $hide_current = $slot->getOption('hide_current', false, $settings['culture']);
    $use_flag = in_array('icon', $displays) ? true : false;
    $use_label = (in_array('title', $displays) || (!$use_flag)) ? true : false;

    return array(
      'relative_slug' => $relative_slug,
      'localizations' => $localizations,
      'displays' => $displays,
      'hide_current' => $hide_current,
      'use_flag' => $use_flag,
      'use_label' => $use_label
    );
  }
}
