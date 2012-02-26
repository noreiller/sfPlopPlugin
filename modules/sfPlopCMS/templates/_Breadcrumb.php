<ul class="w-menu w-menu-i">
  <li><?php echo __('You are here') ?> :</li>

  <?php foreach ($settings['page']->getAncestors() as $i => $node): ?>

    <?php $node->setCulture($settings['culture']); ?>
    <li <?php if ($i == 0): ?>class="first"<?php endif; ?>>
      <?php echo link_to(
        content_tag('strong', $node->getTitle()),
        '@sf_plop_page_show?slug=' . $node->getSlug()
      ) ?>
    </li>

  <?php endforeach; ?>

  <?php $settings['page']->setCulture($settings['culture']); ?>
  <li class="<?php echo ($settings['page']->hasParent()) ? 'last' : 'first' ?>">
    <strong><?php echo $settings['page']->getTitle() ?></strong>
  </li>
</ul>