<?php $page = $settings['page']; ?>
<?php $title = $slot->getOption('title', '', $settings['culture']); ?>
<?php $use_dropdown_menu = $slot->getOption('use_dropdown_menu', false, $settings['culture']); ?>
<?php if (count($localizations) > 1 || $slot->getOption('force_display', false, $settings['culture'])): ?>
  <ul class="w-menu">
    <?php if ($use_dropdown_menu): ?>
      <li class="w-menu-dd">
        <span class="element lang-<?php echo $settings['culture']; ?>"><?php echo $title == ''
          ? ucfirst(format_language($settings['culture'], $settings['culture']))
          : $title
        ?></span>
        <ul>
    <?php endif; ?>
      <?php foreach ($localizations as $localization): ?>
        <?php $current = ($localization == $settings['culture']); ?>
        <?php if (!$current || ($current && !$hide_current)): ?>
          <li class="<?php if ($current): ?>current<?php endif; ?>">
            <?php $node_title = ucfirst(format_language($localization, $localization)); ?>
            <?php $node_label = (
              ($use_flag
                ? image_flag($localization, array('alt' => $node_title))
                : null
              )
                . ($use_label
                  ? content_tag('strong', $node_title)
                  : null
                )
            ); ?>
            <?php echo link_to_unless(
              (($relative_slug == '') && ($settings['culture'] == $localization)),
              $node_label,
              '@sf_plop_page_show?sf_culture=' . $localization
                . '&slug=' . ($relative_slug != '' ? $relative_slug : $page->getSlug()),
              array(
                'class' => 'element lang-' . $localization,
                'title' => $node_title
              )); ?>
          </li>
        <?php endif; ?>
        <?php $current = false; ?>
      <?php endforeach; ?>

    <?php if ($title): ?>
        </ul>
      </li>
    <?php endif; ?>

  </ul>
<?php endif; ?>