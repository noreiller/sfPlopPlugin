<?php if (!$sf_request->isXmlHttprequest()): ?>
  <h2>
    <strong><?php echo __('Edit slot "%s"', array('%s' => $form->getObject()->getTemplateName()), '', 'plopAdmin') ?></strong>
  </h2>
<?php endif; ?>

<form action="<?php echo url_for('@sf_plop_slot_edit_options?sf_culture=' . $culture . '&id=' . $form->getObject()->getId()) ?>"
  method="POST" class="w-form w-form-i w-admin-edit-slot" rel="<?php echo $form->getObject()->getId() ?>">
  
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
