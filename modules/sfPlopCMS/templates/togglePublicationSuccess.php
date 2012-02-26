<?php if ($page->isPublished()): ?>
  <?php echo __('The page has been published.', '', 'plopAdmin') ?>
<?php else: ?>
  <?php echo __('The page has been unpublished.', '', 'plopAdmin') ?>
<?php endif; ?>
