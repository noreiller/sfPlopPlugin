<?php slot('sf_plop_theme', sfPlop::getAdminTheme($sf_user->getProfile()->getTheme(), true, true)) ?>

<?php slot('sf_plop_admin'); ?>
  <?php include_partial('sfPlopDashboard/toolbarDashboard', array(
    'tabs' => $tabs,
    'sub' => $sub,
  )) ?>
<?php end_slot(); ?>

<<?php echo html5Tag('section'); ?> class="section lcr">
  <div class="content RichText">
    <h1><?php echo __('Settings', '', 'plopAdmin') ?></h1>
    <?php if (isset($tabs[$sub])): ?>
      <h2><?php echo __($tabs[$sub], '', 'plopAdmin') ?></h2>
    <?php endif; ?>

    <?php if (isset($form)): ?>

      <?php if ($sf_user->getFlash('form.saved') === true): ?>
        <p class="notification valid"><?php echo __('The modifications have been saved.', '', 'plopAdmin') ?></p>
      <?php elseif ($sf_user->getFlash('form.saved') === false): ?>
        <p class="notification error"><?php echo __('The modifications have not been saved.', '', 'plopAdmin') ?></p>
      <?php endif; ?>

      <form action="<?php echo url_for('@sf_plop_dashboard_settings?sub=' . $sub) ?>" method="post" class="w-form">

        <?php include_partial('sfPlopCMS/form_fields', array('form' => $form)) ?>

        <div class="form-row form-row-buttons">
          <input type="submit" value="<?php echo __('save', '', 'plopAdmin') ?>" />
          <a href="<?php echo url_for('@sf_plop_dashboard_settings') ?>" class="w-form-cancel">
            <?php echo __('cancel', '', 'plopAdmin') ?>
          </a>
        </div>
      </form>

    <?php else: ?>

      <p><?php echo __('Please select a category.', '', 'plopAdmin') ?></p>
      <ul>
      <?php foreach ($tabs as $key => $value): ?>
        <li>
          <?php echo link_to_unless(
            ($sub == $key),
            __($value, '', 'plopAdmin'),
            '@sf_plop_dashboard_settings?sub=' . $key
          ) ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <?php endif; ?>
  </div>
</<?php echo html5Tag('section'); ?>>

<?php echo clear(); ?>