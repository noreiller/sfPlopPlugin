<?php if (!$sf_request->isXmlHttprequest()): ?>
  <h2>
    <strong><?php echo __('Create a new page', '', 'plopAdmin') ?></strong>
  </h2>
<?php endif; ?>

<form action="<?php echo url_for('@sf_plop_page_create?sf_culture=' . $culture) ?>"
  method="POST" class="w-form w-form-i">
  
  <?php include_partial('sfPlopCMS/form_fields', array(
    'form' => $form,
    'use_legend' => false
  )) ?>
    
  <div class="form-row form-row-buttons">
    <input type="submit" value="<?php echo __('save', '', 'plopAdmin') ?>" />
    <a href="<?php echo url_for('@sf_plop_homepage?sf_culture=' . $culture) ?>" class="w-form-cancel">
      <?php echo __('cancel', '', 'plopAdmin') ?>
    </a>
  </div>
</form>
