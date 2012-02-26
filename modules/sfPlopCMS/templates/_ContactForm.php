<?php if ($sf_user->hasAttribute('contactForm_valid') && $sf_user->getAttribute('contactForm_valid') === true): ?>

  <ul>
    <li><strong><?php echo __('Your message has been sent. Thank you.') ?></strong></li>
    <li><?php echo link_to(
      __('Send another message'),
      '@sf_plop_page_show?sf_culture=' . $settings['culture'] . '&slug=' . $settings['page']->getSlug()
    ) ?></li>
  </ul>

<?php else: ?>

  <?php $q = 'sf_culture=' . $settings['culture'] . '&r=' . $settings['page']->getSlug() . '&c=' . $slot->getId() ?>
  <?php if ($sf_user->hasAttribute('contactForm_form')) $form = $sf_user->getAttribute('contactForm_form'); ?>

  <form action="<?php echo url_for('@sf_plop_contact?' . $q) ?>" method="post" class="w-form">

    <h2>
      <strong><?php echo isset($title) ? $title : __('Contact form') ?></strong> 
      <?php echo __('Please complete the fields below :') ?>
    </h2>

    <?php if ($sf_user->hasAttribute('contactForm_valid') && $sf_user->getAttribute('contactForm_valid') === false): ?>
      <ul class="error_list">
        <li><?php echo __('Your message has not been sent due to an internal error. Sorry.') ?></li>
      </ul>
    <?php endif; ?>

    <?php include_partial('sfPlopCMS/form_fields', array('form' => $form)) ?>

    <div class="form-row form-row-buttons">
      <input type="submit" value="<?php echo __('Send') ?>" />
    </div>
  </form>

<?php endif; ?>

<?php $sf_user->getAttributeHolder()->remove('contactForm_valid'); ?>
<?php $sf_user->getAttributeHolder()->remove('contactForm_form'); ?>