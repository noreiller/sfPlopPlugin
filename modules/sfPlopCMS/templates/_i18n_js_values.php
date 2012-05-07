<?php $user_culture = $sf_user->isAuthenticated()
  ? $sf_user->getProfile()->getCulture()
  : $culture; ?>
<?php $sf_user->setCulture($user_culture) ?>

<script type="text/javascript">
  if (window.sfPlopAdmin)
    sfPlopAdmin._tempVal = {
      'i18n.confirm' : "<?php echo __('Are you sure ?', '', 'plopAdmin') ?>",
      'i18n.information' : "<?php echo __('Information', '', 'plopAdmin') ?>",
      'i18n.error' : "<?php echo __('Error', '', 'plopAdmin') ?>",
      'i18n.slot_creation_success' : "<?php echo __('The block has been created.', '', 'plopAdmin') ?>",
      'i18n.slot_edition_success' : "<?php echo __('The block has been updated.', '', 'plopAdmin') ?>",
      'i18n.admin_theme_switch_success' : "<?php echo __('The theme has been saved.', '', 'plopAdmin') ?>",
      'richtext-editor' : "<?php echo sfPlop::get('sf_plop_richtext_editor') ?>",
      'richtext-editor-bridge' : "<?php echo sfPlop::get('sf_plop_richtext_editor_' . sfPlop::get('sf_plop_richtext_editor') . '_bridge')?>",
      'richtext-editor-script' : <?php echo json_encode(sfPlop::get('sf_plop_richtext_editor_' . sfPlop::get('sf_plop_richtext_editor') . '_script')) ?>,
      'richtext-editor-css' : "<?php echo sfPlop::get('sf_plop_richtext_editor_' . sfPlop::get('sf_plop_richtext_editor') . '_css')?>",
      'aloha.i18n.current' : "<?php echo $culture ?>",
      'aloha.repository.url' : "<?php echo url_for('@sf_plop_ws_repository?sf_culture=' . $culture) ?>",
      'login_url' : "<?php echo url_for('@sf_plop_signin?sf_culture=' . $culture) ?>"
    };
</script>

<?php $sf_user->setCulture($culture) ?>
