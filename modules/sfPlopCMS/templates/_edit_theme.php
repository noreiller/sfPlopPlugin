<?php if (!$sf_request->isXmlHttprequest()): ?>
  <h2>
    <strong><?php echo __('Edit theme', '', 'plopAdmin') ?></strong>
  </h2>
<?php endif; ?>

<form action="<?php echo url_for('@sf_plop_theme_edit?sf_culture=' . $culture) ?>" 
  method="POST" class="w-form w-form-i w-ajax w-theme-editor">
  
  <?php include_partial('sfPlopCMS/form_fields', array('form' => $form)) ?>
    
  <div class="form-row form-row-buttons">
    <input type="submit" value="<?php echo __('save', '', 'plopAdmin') ?>" />
    <input type="reset" value="<?php echo __('reset', '', 'plopAdmin') ?>" class="w-form-reset" />
    <a href="<?php echo url_for('@sf_plop_homepage?sf_culture=' . $culture) ?>" class="w-form-cancel">
      <?php echo __('cancel', '', 'plopAdmin') ?>
    </a>
  </div>
</form>
