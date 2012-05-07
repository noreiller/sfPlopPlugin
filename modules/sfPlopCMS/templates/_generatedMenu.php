<?php $culture = $settings['culture']; ?>

<ul class="<?php echo $menu_class ?> <?php if ($useAjax): ?>w-menu-ajax<?php endif; ?>">
  <?php $last_valid_node = null; ?>
  <?php $last_valid_level = null; ?>
  <?php $is_ancestor_hidden = false; ?>
  <?php $level_blocker_node = null; ?>
  <?php $level = $relative_level; ?>
  <?php foreach ($level1_nodes as $i => $node): ?>
    <?php $node->setCulture($culture); ?>

    <?php if ($last_valid_node && !isset($level_depth)): ?>
      <?php if ($level_blocker_node && $node->isDescendantOf($level_blocker_node)): ?>
        <?php $is_ancestor_hidden = true; ?>
      <?php else: ?>
        <?php $level_blocker_node = null; ?>
        <?php $is_ancestor_hidden = false; ?>
      <?php endif; ?>

      <?php $level = $is_ancestor_hidden ? $node->getLevel() - 1 : $node->getLevel(); ?>
    <?php else: ?>
      <?php $level = $node->getLevel() ?>
    <?php endif; ?>

    <?php if (
      $node->isRoot()
      || ($node->isPublished()
        && ($show_hidden_parent
          || (!$show_hidden_parent && $level == $node->getLevel()))
    )): ?>

      <?php if ($last_valid_node): ?>
        <?php if ($last_valid_level == $level): ?>
          </li>
        <?php elseif (!$last_valid_node->isRoot() && $last_valid_level < $level): ?>
          <ul>
        <?php elseif ($last_valid_level > $level): ?>
          <?php echo str_repeat('</li></ul></li>', $last_valid_level - $level); ?>
        <?php endif; ?>
      <?php endif; ?>

      <?php $node_subtitle = $node->getSubtitle($culture) ?>
      <?php $node_title = ($node_subtitle != '') ? $node_subtitle : $node->getTitle(); ?>
      <?php $node_label =
        (($use_icon && $node->getIcon()) ? image_tag($node->getIcon(), array('alt' => $node_title)) : null)
          . ($use_title ? content_tag('strong', $node->getTitle()) : null)
          . ($use_subtitle ? content_tag('em', $node_subtitle) : null); ?>
      <?php $is_current = $node->getSlug() == $settings['page']->getSlug(); ?>
      <?php $is_current_ancestor = $settings['page']->isDescendantOf($node) && !$node->isRoot(); ?>
      <?php
        $class = array('lvl' . $level);
         if ($is_current) $class []= 'active';
         if ($is_current_ancestor) $class []= 'current';
      ?>

      <li class="<?php echo implode(' ', $class) ?>">
        <?php echo link_to_unless(
          $node->isCategory(),
          $node_label,
          '@sf_plop_page_show?slug=' . $node->getSlug(),
          array(
            'class' => 'element',
            'title' => $node_title
        )) . "\n" ?>

      <?php $last_valid_node = $node; ?>
      <?php $last_valid_level = $level; ?>
    <?php else: ?>
      <?php if (!$is_ancestor_hidden) $level_blocker_node = $node; ?>
    <?php endif; ?>
  <?php endforeach; ?>
</ul>

