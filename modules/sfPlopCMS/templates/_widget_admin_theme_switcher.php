<form action="<?php echo url_for('@sf_plop_profile_theme') ?>" method="POST"
      class="element w-form w-ajax w-button w-button-light w-admin-theme-switch">
  <label for="admin-theme-switch"><?php echo __('Theme', '', 'plopAdmin') ?></label>
  <select id="admin-theme-switch" name="theme">
    <?php $admin_theme = sfPlop::get('sf_plop_admin_theme'); ?>
    <?php foreach(sfPlop::get('sf_plop_loaded_admin_themes') as $name => $infos): ?>
      <option value="<?php echo $name; ?>"
        <?php if ($name == $admin_theme):?> selected="selected"<?php endif; ?>
        data-color="<?php echo $infos['color']; ?>"
      >
        <?php echo $infos['description']; ?>
      </option>
    <?php endforeach; ?>
  </select>
  <input type="submit" value="<?php echo __('Submit', '', 'plopAdmin') ?>" />
</form>
