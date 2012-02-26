<?php $user_culture = $sf_user->getCulture(); ?>

<<?php echo html5Tag('nav');?> class="nav">
  <?php $links = sfPlop::getSafePluginLinks() ?>
	<?php $modules = sfPlop::getSafePluginModules() ?>
	
	<?php if (count($links) > 0): ?>
		<ul class="w-menu">
			<?php foreach ($links as $name => $options): ?>
        <?php if (!isset($options['module']) 
          || (isset($options['module']) && $sf_user->hasCredential($options['module']))
        ): ?>
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
          <?php if (isset($options['module']) && isset($modules[$options['module']])) 
            unset($modules[$options['module']]); ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <?php foreach ($modules as $name => $options): ?>
    <?php if (!$sf_user->hasCredential($name)) unset($modules[$name]); ?>
  <?php endforeach ?>

  <?php include_partial('sfPlopCMS/toolbar_quick_links', array('modules' => $modules)) ?>
</<?php echo html5Tag('nav'); ?>>