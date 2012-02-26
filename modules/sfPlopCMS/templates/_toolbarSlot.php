<?php $user_culture = $sf_user->isAuthenticated()
  ? $sf_user->getProfile()->getCulture()
  : $culture; ?>
<?php $sf_user->setCulture($user_culture) ?>

<span class="title">
  <span class="w w-rank"><?php echo $slot->getRank() ?></span>
  <span class="w w-title" title="<?php echo __($slot->getTemplateName(), '', 'plopAdmin') ?>">
    <?php echo __($slot->getTemplateName(), '', 'plopAdmin') ?>
  </span>
  <?php echo widgetIndicator(!$slot->isEditable(), 'edit',
    __('This indicates if the block is a template (lock icon).', '', 'plopAdmin'),
    array('rel' => $slot->getId())
  ) ?>
  <?php echo widgetIndicator($slot->isPublished(), 'publish',
    __('This indicates if the block is published (green tick) or not (red bullet).', '', 'plopAdmin'),
    array('rel' => $slot->getId())
  ) ?>
</span>

<ul class="w-menu">
  <li class="w-menu-dd">
    <?php echo link_to(
      __('Edition', '', 'plopAdmin'),
      '@sf_plop_slot_toolbar?sf_culture=' . $culture . '&id=' . $slotConfig->getId(),
      array('class' => 'element w w-edition')
    ) ?>
  </li>
</ul>

<?php $sf_user->setCulture($culture) ?>