<<?php echo html5Tag('nav'); ?> class="nav">
  <ul class="w-menu menu-custom">
    <li>
      <?php echo link_to(
        __('Informations', '', 'plopAdmin'),
        '@sf_plop_dashboard',
        'class=element'
      ) ?>
    </li>
    <li class="w-menu-dd w-menu-dd-left">
      <?php echo link_to(
        __('Settings', '', 'plopAdmin'),
        '@sf_plop_dashboard_settings',
        'class=element'
      ) ?>
      <ul>
        <?php foreach ($tabs as $key => $value): ?>
          <li>
            <?php echo link_to_unless(
              ($sub == $key),
              __($value, '', 'plopAdmin'),
              '@sf_plop_dashboard_settings?sub=' . $key,
              'class=element'
            ) ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </li>
    <li>
      <?php echo link_to(
        __('Themes', '', 'plopAdmin'),
        '@sf_plop_dashboard_themes',
        'class=element'
      ) ?>
    </li>
    <?php if (in_array('sfGuardUser', array_keys(sfPlop::getSafePluginModules()))): ?>
      <li>
        <?php echo link_to(
          __('Credentials', '', 'plopAdmin'),
          '@sf_plop_dashboard_permissions',
          'class=element'
        ) ?>
      </li>
    <?php endif; ?>

    <?php if (
      (sfPlop::get('sfPlopCMS_use_statistics') == true)
      && (sfPlop::get('sfPlopCMS_statistics_reports_url') != '')
    ): ?>
      <li>
        <a class="element" target="_blank" href="<?php echo sfPlop::get('sfPlopCMS_statistics_reports_url') ?>">
          <?php echo __('Statistics reports', '', 'plopAdmin') ?>
        </a>
      </li>
    <?php endif; ?>
  </ul>

  <?php include_partial('sfPlopCMS/toolbar_quick_links') ?>
</<?php echo html5Tag('nav'); ?>>