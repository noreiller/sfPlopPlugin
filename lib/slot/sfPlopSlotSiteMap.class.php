<?php

class sfPlopSlotSiteMap extends sfPlopSlotNone
{
  public function getSlotValue($slot, $settings)
  {
    return get_partial('generatedMenu', array(
      'slot' => $slot,
      'settings' => $settings,
    ) + $this->getParams($slot, $settings));
  }

  protected function getParams($slot, $settings)
  {
    $root = ($settings['page']->isRoot()) ? $settings['page'] : sfPlopPageQuery::create()->findRoot();
    $q = sfPlopPageQuery::create();
    $level1_nodes = $root->getBranch($q);
    $displays = $slot->getOption('displays', array(), $settings['culture']);
    $use_icon = in_array('icon', $displays) ? true : false;
    $use_subtitle = in_array('subtitle', $displays) ? true : false;
    $use_title = (in_array('title', $displays) || (!$use_icon && !$use_subtitle)) ? true : false;

    return array(
      'relative_level' => 1,
      'relative_page' => false,
      'level1_nodes' => $level1_nodes,
      'level_depth' => null,
      'show_hidden_parent' => true,
      'displays' => $displays,
      'use_icon' => $use_icon,
      'use_subtitle' => $use_subtitle,
      'use_title' => $use_title,
      'useAjax' => false,
      'menu_class' => 'w-list'
    );
  }
}
