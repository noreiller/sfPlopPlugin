<?php

class sfPlopSlotSecondNavigation extends sfPlopSlotMainNavigation
{
  public function getFields($settings)
  {
    $level_depth_choices = array('' => '');
    for ($i = 1; $i <= sfPlopPagePeer::getMaxLevel(); $i++)
      $level_depth_choices[$i] = $i;
    return array(
      'relative_page' => new sfWidgetFormPlopChoicePageSlug(array(
        'label' => 'Page'
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
      'relative_page' => new sfValidatorPlopChoicePageSlug(),
      'displays' => new sfValidatorChoice(array(
        'choices' => array_keys(sfPlop::get('sf_plop_menu_items')),
        'multiple' => true,
        'required' => false
      )),
      'level_depth' => new sfValidatorInteger(array(
        'required' => false,
        'min' => 1,
        'max' => sfPlopPagePeer::getMaxLevel()
      ))

    );
  }

  protected function getParams($slot, $settings)
  {
    $page = $settings['page'];
    $relative_slug = $slot->getOption('relative_page', null, $settings['culture']);
    if ($relative_slug === null)
      $relative_page = ($page->isRoot()) ? $page : sfPlopPageQuery::create()->findRoot();
    elseif ($page->getSlug() == $relative_slug)
      $relative_page = $page;
    else
      $relative_page = sfPlopPagePeer::retrieveBySlug($relative_slug);

    if (!$relative_page)
      $relative_page = ($page->isRoot()) ? $page : sfPlopPageQuery::create()->findRoot();

    $level_depth = $slot->getOption('level_depth', null, $settings['culture']);
    if (isset($level_depth))
      $q = sfPlopPageQuery::create()
        ->addCond('cond1', sfPlopPagePeer::TREE_LEVEL, $relative_page->getLevel() + $level_depth, Criteria::LESS_EQUAL)
        ->addCond('cond2', sfPlopPagePeer::TREE_LEVEL, null, Criteria::ISNULL)
        ->combine(array('cond1', 'cond2'), Criteria::LOGICAL_OR)
      ;
    else
      $q = sfPlopPageQuery::create();

    $level1_nodes = $relative_page->getDescendants($q);
    $useAjax = sfPlop::get('sf_plop_use_ajax') === true ? true : false;
    $displays = $slot->getOption('displays', array(), $settings['culture']);
    $use_icon = in_array('icon', $displays) ? true : false;
    $use_subtitle = in_array('subtitle', $displays) ? true : false;
    $use_title = (in_array('title', $displays) || (!$use_icon && !$use_subtitle)) ? true : false;

    return array(
      'relative_level' => $relative_page->getLevel() + 1,
      'relative_page' => $relative_page,
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
