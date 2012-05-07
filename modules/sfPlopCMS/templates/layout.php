<!doctype html>
<?php $culture = $sf_user->getCulture() ?>
<!--[if lt IE 7 ]> <html lang="<?php echo $culture ?>" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="<?php echo $culture ?>" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="<?php echo $culture ?>" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="<?php echo $culture ?>" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="<?php echo $culture ?>" class="no-js" > <!--<![endif]-->
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

  <?php include_http_metas() ?>
  <?php include_title() ?>
  <?php include_metas() ?>

  <meta name="viewport" content="width=device-width" />
  <link rel="shortcut icon" href="<?php echo sfPlop::get('sf_plop_custom_favicon') ?>" />
  <link rel="apple-touch-icon" href="<?php echo sfPlop::get('sf_plop_custom_webapp_favicon') ?>" />

  <?php include_stylesheets() ?>

  <script src="/sfPlopPlugin/vendor/html5-boilerplate/js/libs/modernizr-2.5.2.min.js"></script>
</head>
<body class="<?php include_slot('sf_plop_theme') ?>">

  <?php if (!has_slot('sf_plop_admin') && $sf_user->isAuthenticated()): ?>
    <?php slot('sf_plop_admin'); ?>
      <?php include_partial('sfPlopCMS/toolbarCommon') ?>
    <?php end_slot(); ?>
  <?php endif; ?>
  <?php if (has_slot('sf_plop_admin')): ?>
    <<?php echo html5Tag('header'); ?> class="w-toolbar">
      <?php include_slot('sf_plop_admin'); ?>
    </<?php echo html5Tag('header'); ?>>
  <?php endif; ?>

  <div id="container" class="container" role="main">
    <?php echo $sf_content ?>
  </div>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="/sfPlopPlugin/vendor/jquery/js/jquery-1.6.1.min.js"><\/script>')</script>

  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/jquery-ui.min.js"></script>
  <script>window.jQuery.ui || document.write('<script src="/sfPlopPlugin/vendor/jquery/js/jquery-ui-1.8.17.min.js"><\/script>')</script>

  <?php include_javascripts() ?>
  <?php if ($sf_user->isAuthenticated() && $sf_user->isSuperAdmin())
    include_partial('sfPlopCMS/i18n_js_values', array('culture' => $culture)) ?>
</body>
</html>