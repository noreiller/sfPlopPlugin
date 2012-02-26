<?php $url_suffix = isset($url_suffix) ? $url_suffix : null; ?>
<form action="<?php echo url_for('@sf_plop_register' . $url_suffix) ?>"
  method="post" class="w-form w-form-i">

	<h2>
	  <strong><?php echo __('Register!') ?></strong>
	  <?php echo __('Please complete the fields below :') ?>
	</h2>

  <?php include_partial('sfPlopCMS/form_fields', array('form' => $form)) ?>

  <div class="form-row form-row-buttons">
    <input type="submit" value="<?php echo __('Register') ?>" />
  </div>
</form>
