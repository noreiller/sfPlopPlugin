<?php if (!$sf_request->isXmlHttprequest()): ?>
  <h2>
    <strong><?php echo __('Copy a block to another page', '', 'plopAdmin') ?></strong>
  </h2>
<?php endif; ?>

<form action="<?php echo url_for('@sf_plop_page_copy_slot?sf_culture=' . $culture . '&slug=' . $page_ref->getSlug() . '&slot_id=' . $slot_ref->getId()) ?>"
  method="POST" class="w-form w-form-i">

  <?php include_partial('sfPlopCMS/form_fields', array('form' => $form)) ?>

  <div class="form-row form-row-buttons">
    <input type="submit" value="<?php echo __('save', '', 'plopAdmin') ?>" />
    <a href="<?php echo url_for('@sf_plop_homepage?sf_culture=' . $culture) ?>" class="w-form-cancel">
      <?php echo __('cancel', '', 'plopAdmin') ?>
    </a>
  </div>
</form>
