<?php $url_suffix = isset($url_suffix) ? $url_suffix : null; ?>
<?php if (!$url_suffix && preg_match('/\/plop\/(.*)\/login/', url_for($sf_request->getParameterHolder()->getAll())) == false) 
  $url_suffix = '?url=' . url_for($sf_request->getParameterHolder()->getAll()); ?>
<form action="<?php echo url_for('@sf_plop_signin' . $url_suffix) ?>" 
  method="post" class="w-form">

	<h2>
	  <strong><?php echo __('Login!') ?></strong> 
	  <?php echo __('Please complete the fields below :') ?>
	</h2>
    
  <?php include_partial('sfPlopCMS/form_fields', array('form' => $form)) ?>
  
  <div class="form-row form-row-buttons">
    <input type="submit" value="<?php echo __('sign in') ?>" />
  </div>

  <?php if (sfPlop::get('sf_plop_allow_registration')): ?>
  <?php echo link_to(__('Register'), '@sf_plop_register'
  	. (strpos($url_suffix, '?url=') !== false
  		? $url_suffix
  		: '?url=' . url_for($sf_request->getParameterHolder()->getAll())
		)); ?>
	<?php endif; ?>
</form>