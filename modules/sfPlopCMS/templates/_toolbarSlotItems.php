<?php $user_culture = $sf_user->isAuthenticated()
  ? $sf_user->getProfile()->getCulture()
  : $culture; ?>
<?php $sf_user->setCulture($user_culture) ?>

<ul>
  <?php if ($slot->isContentEditable() || $slot->isContentOptionable()): ?>
    <li>
      <?php echo link_to(
        __('Edit content', '', 'plopAdmin'),
        '@sf_plop_slot_edit?sf_culture=' . $culture . '&id=' . $slotConfig->getId(),
        array(
          'class' => 'element w-admin-content' . ($slot->isContentEditable() ? ' w-admin-rich' : null),
          'title' => __('Edit content', '', 'plopAdmin')
      )) ?>
    </li>
  <?php endif; ?>
  <?php if ($isTemplate): ?>
    <li>
      <?php echo link_to(
        __('Edit options', '', 'plopAdmin'),
        '@sf_plop_slot_edit_options?sf_culture=' . $culture . '&id=' . $slot->getId(),
        array(
          'class' => 'element w-ajax w-ajax-d',
          'title' => __('Edit options', '', 'plopAdmin')
      )) ?>
    </li>
    <?php if (!$slot->isArea()): ?>
      <li>
        <?php echo link_to(
          __('Copy to another page', '', 'plopAdmin'),
          '@sf_plop_page_copy_slot?sf_culture=' . $culture . '&slug=' . $page->getSlug() . '&slot_id=' . $slot->getId(),
          array(
            'class' => 'element w-ajax w-ajax-d',
            'title' => __('Copy to another page', '', 'plopAdmin')
        )) ?>
      </li>
    <?php endif; ?>
    <li>
      <?php if ($slot->isPublished()): ?>
        <?php $label = 'Unpublish'; ?>
      <?php else: ?>
        <?php $label = 'Publish'; ?>
      <?php endif; ?>
      <?php echo link_to(
         __($label, '', 'plopAdmin'),
        '@sf_plop_slot_toggle_publication?sf_culture=' . $culture . '&id=' . $slot->getId(),
        array(
          'class' => 'element w-ajax w-ajax-n w-publish w-confirm',
          'rel' => $slot->getId(),
          'title' => __($label, '', 'plopAdmin')
      )) ?>
    </li>
    <li>
      <?php if ($slot->isEditable()): ?>
        <?php $label = 'Make uneditable'; ?>
      <?php else: ?>
        <?php $label = 'Make editable'; ?>
      <?php endif; ?>
      <?php echo link_to(
         __($label, '', 'plopAdmin'),
        '@sf_plop_slot_toggle_edition?sf_culture=' . $culture . '&id=' . $slot->getId(),
        array(
          'class' => 'element w-ajax w-ajax-n w-edit w-confirm',
          'rel' => $slot->getId(),
          'title' => __($label, '', 'plopAdmin')
      )) ?>
    </li>
    <?php if (!$slot->isFirst()): ?>
      <li>
        <?php echo link_to(
          __('Up this block', '', 'plopAdmin'),
          '@sf_plop_slot_move?sf_culture=' . $culture . '&direction=up&id=' . $slot->getId(),
          array(
            'class' => 'element w-ajax w-ajax-n w-admin-up',
            'title' => __('Up this block', '', 'plopAdmin')
        )) ?>
      </li>
    <?php endif ?>
    <?php if (!$slot->isLast()): ?>
      <li>
        <?php echo link_to(
          __('Down this block', '', 'plopAdmin'),
          '@sf_plop_slot_move?sf_culture=' . $culture . '&direction=down&id=' . $slot->getId(),
          array(
            'class' => 'element w-ajax w-ajax-n w-admin-down',
            'title' => __('Down this block', '', 'plopAdmin')
        )) ?>
      </li>
    <?php endif ?>
    <?php if (!$slot->isPublished() && $slotConfig->isTranslated()): ?>
      <li>
        <?php echo link_to(
          __('Reset', '', 'plopAdmin'),
          '@sf_plop_slot_reset?sf_culture=' . $culture . '&id=' . $slotConfig->getId(),
          array(
            'class' => 'element w-ajax w-ajax-n w-confirm w-admin-refresh',
            'rel' => $slot->getId(),
            'title' => __('Reset', '', 'plopAdmin')
        )) ?>
      </li>
    <?php endif ?>
    <?php if (!$slot->isPublished()): ?>
      <li>
        <?php echo link_to(
          __('Delete', '', 'plopAdmin'),
          '@sf_plop_slot_delete?sf_culture=' . $culture . '&id=' . $slot->getId(),
          array(
            'class' => 'element w-ajax w-ajax-n w-confirm w-admin-delete',
            'title' => __('Delete', '', 'plopAdmin')
        )) ?>
      </li>
    <?php endif; ?>
  <?php endif; ?>
  <li>
    <form class="w-form w-form-i">
      <button type="button" name="close-toolbar" id="close-toolbar" class="close-toolbar">
        <?php echo __('Close', '', 'plopAdmin') ?>
      </button>
    </form>
  </li>
</ul>
