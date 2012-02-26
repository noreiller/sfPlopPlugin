<?php use_plop_theme(sfPlop::getAdminTheme($sf_user->getProfile()->getTheme(), true, true), $tmp_theme ? $tmp_theme : null); ?>

<?php slot('sf_plop_admin'); ?>
  <?php include_partial('sfPlopDashboard/toolbarDashboard', array(
    'tabs' => $tabs,
    'sub' => $sub,
  )) ?>
<?php end_slot(); ?>

<<?php echo html5Tag('section'); ?> class="section lcr RichText">
  <div class="w-block content RichText">

    <?php if (isset($use_theme)): ?>
      <p>
        <?php echo __('You have chosen the theme : "%s".', array(
          '%s' => content_tag('strong', $use_theme)
        ), 'plopAdmin') ?>
      </p>
    <?php endif; ?>

    <?php if ($tmp_theme || !$use_theme): ?>
      <p>
        <?php echo __('The current theme is : "%s".', array(
          '%s' => content_tag('strong', $theme)
        ), 'plopAdmin') ?>
      </p>
    <?php endif; ?>

    <?php if ($sf_user->getFlash('form.saved') === true): ?>
      <p class="notification valid"><?php echo __('The modifications have been saved.', '', 'plopAdmin') ?></p>
    <?php elseif ($sf_user->getFlash('form.saved') === false): ?>
      <p class="notification error"><?php echo __('The modifications have not been saved.', '', 'plopAdmin') ?></p>
    <?php endif; ?>

    <form action="<?php echo url_for('@sf_plop_dashboard_themes') ?>" method="post" class="w-form w-form-i">

      <?php include_partial('sfPlopCMS/form_fields', array('form' => $form)) ?>

      <div class="form-row form-row-buttons">
        <input type="submit" value="<?php echo __('submit', '', 'plopAdmin') ?>" />
        <?php echo link_to(
          __('cancel', '', 'plopAdmin'),
          '@sf_plop_dashboard_themes',
          array('class' => 'w-form-cancel')
        ) ?>
      </div>
    </form>

    <hr />

  </div>
</<?php echo html5Tag('section'); ?>>

<?php include_partial('content_example') ?>