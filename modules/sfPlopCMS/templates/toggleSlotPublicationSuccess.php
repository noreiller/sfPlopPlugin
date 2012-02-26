<?php if ($slot->isPublished()): ?>
  <?php echo __('The block has been published.', '', 'plopAdmin') ?>
<?php else: ?>
  <?php echo __('The block has been unpublished.', '', 'plopAdmin') ?>
<?php endif; ?>
