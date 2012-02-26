<?php

class sfPlopSlotMainNavigation extends sfPlopSlotStandard
{
  public function isContentOptionable() { return true; }

  public function isContentEditable() { return false; }

  public function getDefaultValue() { return array(); }

  public function getFields($slot)
  {
    $level_depth_choices = array('' => '');
    for ($i = 1; $i <= sfPlopPagePeer::getMaxLevel(); $i++)
      $level_depth_choices[$i] = $i;
    return array(
      'hide_root' => new sfWidgetFormInputCheckbox(array(
        'value_attribute_value' => true,
        'label' => 'Hide homepage ?'
      )),
      'displays' => new sfWidgetFormChoice(array(
        'choices' => sfPlop::get('sf_plop_menu_items'),
        'label' => 'Elements to display',
        'multiple' => true,
        'expanded' => true
      )),
      'level_depth' => new sfWidgetFormChoice(array(
        'choices' => $level_depth_choices,
        'label' => 'Depth of the menu'
      ))
    );
  }

  public function getValidators($slot)
  {
    return array(
      'hide_root' => new sfValidatorBoolean(),
      'displays' => new sfValidatorChoice(array(
        'required' => false,
        'choices' => array_keys(sfPlop::get('sf_plop_menu_items')),
        'multiple' => true
      )),
      'level_depth' => new sfValidatorInteger(array(
        'required' => false,
        'min' => 1,
        'max' => sfPlopPagePeer::getMaxLevel()
      ))
    );
  }

  public function getSlotValue($slot, $settings)
  {
    return get_partial('generatedMenu', array(
      'slot' => $slot,
      'settings' => $settings,
    ) + $this->getParams($slot, $settings));
  }

  protected function getParams($slot, $settings)
  {
    $page = $settings['page'];
    $culture = $settings['culture'];
    $root = ($page->isRoot()) ? $page : sfPlopPageQuery::create()->findRoot();
    $level_depth = $slot->getOption('level_depth', null, $settings['culture']);
    if (isset($level_depth))
      $q = sfPlopPageQuery::create()
        ->addCond('cond1', sfPlopPagePeer::TREE_LEVEL, $level_depth, Criteria::LESS_EQUAL)
        ->addCond('cond2', sfPlopPagePeer::TREE_LEVEL, null, Criteria::ISNULL)
        ->combine(array('cond1', 'cond2'), Criteria::LOGICAL_OR)
      ;
    else
      $q = sfPlopPageQuery::create();
    $hide_root = $slot->getOption('hide_root', false, $settings['culture']);
    if ($hide_root )
      $level1_nodes = $root->getDescendants($q);
    else
      $level1_nodes = $root->getBranch($q);

    $useAjax = sfPlop::get('sf_plop_use_ajax') === true ? true : false;
    $displays = $slot->getOption('displays', array(), $settings['culture']);
    $use_icon = in_array('icon', $displays) ? true : false;
    $use_subtitle = in_array('subtitle', $displays) ? true : false;
    $use_title = (in_array('title', $displays) || (!$use_icon && !$use_subtitle)) ? true : false;

    return array(
      'relative_level' => 1,
      'relative_page' => $root,
      'level_depth' => $level_depth,
      'show_hidden_parent' => false,
      'level1_nodes' => $level1_nodes,
      'useAjax' => $useAjax,
      'displays' => $displays,
      'use_icon' => $use_icon,
      'use_subtitle' => $use_subtitle,
      'use_title' => $use_title,
      'menu_class' => 'w-menu'
    );
  }
}
