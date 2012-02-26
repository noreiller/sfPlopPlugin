<?php $user_culture = $sf_user->getCulture(); ?>

<ul class="w-menu menu-quick-links">
  <?php if ($sf_user->isAuthenticated()): ?>
    <?php $modules = isset($modules) ? $modules : sfPlop::getSafePluginModules(); ?>
    <?php if (count($modules) > 0): ?>
      <li class="w-menu-dd">
        <span class="element"><?php echo __('Modules', '', 'plopAdmin') ?></span>
        <ul>
          <?php foreach($modules as $name => $options): ?>
            <?php if ($sf_user->hasCredential($name)): ?>
              <?php $label = __($options['name'], '', 'plopAdmin'); ?>
              <?php
                if (isset($options['culture']) && $options['culture'] == 'default')
                  $sf_user->setCulture(sfPlop::get('sf_plop_default_culture'));
              ?>
              <li><?php echo link_to(
                $label,
                $options['route'],
                array('class' => 'element')
              ) ?></li>
              <?php
                if (isset($options['culture']) && $options['culture'] == 'default')
                  $sf_user->setCulture($user_culture);
              ?>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      </li>
    <?php endif; ?>
    <li class="w-menu-dd">
      <span class="element">
        <?php echo __('User : %s', array(
          '%s' => content_tag('strong', $sf_user->getUsername())
        ), 'plopAdmin') ?>
      </span>
      <ul>
        <li><?php echo link_to(
          __('My profile', '', 'plopAdmin'),
          '@sf_plop_profile',
          'class=element') ?></li>
        <li>
          <?php include_partial('sfPlopCMS/widget_admin_theme_switcher') ?>
        </li>
        <?php if ($sf_user->isSuperAdmin()): ?>
          <li>
            <?php echo link_to(
              __('Empty cache', '', 'plopAdmin'),
              '@sf_plop_dashboard_empty_cache',
              array('class' => 'element w-button w-button-light w-ajax w-ajax-n')
            ) ?>
          </li>
        <?php endif; ?>
        <li><?php echo link_to(
          __('Logout', '', 'plopAdmin'),
          '@sf_plop_signout',
          'class=element') ?></li>
      </ul>
    </li>

  <?php else: ?>

    <li><?php echo link_to(
      __('Login', '', 'plopAdmin'),
      '@sf_plop_signin',
      'class=element') ?></li>
    <?php if (sfPlop::get('sf_plop_allow_registration')): ?>
      <li><?php echo link_to(
        __('Register', '', 'plopAdmin'),
        '@sf_plop_register',
        'class=element') ?></li>
    <?php endif; ?>

  <?php endif; ?>
</ul>
