<?php if ($slot->isEditable()): ?>
  <?php echo __('The block has been made editable.', '', 'plopAdmin') ?>
<?php else: ?>
  <?php echo __('The block has been made uneditable.', '', 'plopAdmin') ?>
<?php endif; ?>
