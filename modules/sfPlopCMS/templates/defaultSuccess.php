<?php if (!isset($params)) $params = array(); ?>
<?php if (!isset($is_component)) $is_component = false; ?>
<?php $format = isset($params['format']) ? $params['format'] : null; ?>

<?php if (!isset($params['culture'])) $params['culture'] = $culture; ?>
<?php $user_culture = $sf_user->isAuthenticated()
  ? $sf_user->getProfile()->getCulture()
  : $culture; ?>
<?php $sf_user->setCulture($user_culture) ?>

<?php if ($sf_user->isAuthenticated()): ?>

  <?php slot('sf_plop_theme', 'default '
    . sfPlop::getAdminTheme($sf_user->getProfile()->getTheme(), true, true)) ?>
  <?php slot('sf_plop_admin'); ?>
    <?php include_partial('sfPlopCMS/toolbarCommon') ?>
  <?php end_slot(); ?>

<?php else: ?>

  <?php slot('sf_plop_theme', 'default') ?>
  <<?php echo html5Tag('header'); ?> class="header">
    <h1><?php echo link_to(sfPlop::get('sf_plop_website_title'), '@sf_plop_homepage'); ?></h1>
  </<?php echo html5Tag('header'); ?>>

<?php endif; ?>

<<?php echo html5Tag('section'); ?> class="section <?php echo $format ?>">
  <div class="content">
    <?php if (isset($partial) && $is_component): ?>
      <?php include_component('sfPlopCMS', $partial, $params); ?>
    <?php elseif (isset($partial)): ?>
      <?php include_partial('sfPlopCMS/' . $partial, $params); ?>
    <?php endif; ?>
  </div>
</<?php echo html5Tag('section'); ?>>


<?php if (!$sf_request->isXmlHttprequest()): ?>
  <<?php echo html5Tag('footer'); ?> class="footer">
    <a href="http://www.plop-cms.com" class="w-powered-button"
       title="<?php echo __('Powered by %s', array('%s' => 'Plop CMS')) ?>">
      <?php echo __('Powered by %s', array(
        '%s' => content_tag('em', 'Plop CMS')
      )) ?>
    </a>
  </<?php echo html5Tag('footer'); ?>>
<?php endif; ?>

<?php if (!$sf_user->isAuthenticated() && (sfPlop::get('sf_plop_use_statistics') == true)): ?>
  <?php echo sfPlop::get('sf_plop_statistics_code'); ?>
<?php endif; ?>