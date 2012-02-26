<?php if ($theme != $default_theme && $sf_user->isAuthenticated()): ?>
  <?php use_plop_theme(sfPlop::getAdminTheme($sf_user->getProfile()->getTheme(), false) . ' ' . $theme, $theme); ?>
<?php elseif ($theme != $default_theme): ?>
  <?php use_plop_theme($theme, $theme); ?>
<?php endif; ?>

<form class="w-form w-form-i">

	<h2>
	  <strong><?php echo __('Theme switcher') ?></strong> 
	  <?php echo __('Change the theme of the page by choosing another one') ?>
	</h2>

  <?php include_partial('sfPlopCMS/form_fields', array('form' => $form)) ?>

  <div class="form-row form-row-buttons">
    <input type="submit" value="<?php echo __('submit') ?>" />
  </div>
</form>