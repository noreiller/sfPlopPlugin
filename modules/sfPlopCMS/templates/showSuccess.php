<?php if ($sf_user->isAuthenticated()): ?>

  <?php use_plop_theme(
    ($page->isRoot() ? ' root' : null)
    . ' ' . $page->getTheme()
    . (sfPlop::get('sf_plop_use_image_zoom') == true ? ' img-zoom' : null)
    . sfPlop::getAdminTheme($sf_user->getProfile()->getTheme(), $sf_user->isAuthenticated())
  ); ?>

<?php else: ?>

  <?php use_plop_theme(
    ($page->isRoot() ? ' root' : null)
    . ' ' . $page->getTheme()
    . (sfPlop::get('sf_plop_use_image_zoom') == true ? ' img-zoom' : null)
  ); ?>

<?php endif; ?>

<?php $user_culture = $sf_user->isAuthenticated()
  ? $sf_user->getProfile()->getCulture()
  : $culture; ?>
<?php $sf_user->setCulture($user_culture) ?>

<?php if (!$sf_request->isXmlHttprequest() && $isUserAdmin): ?>

  <?php slot('sf_plop_admin'); ?>
    <?php include_partial('sfPlopCMS/toolbarCMS', array(
      'culture' => $culture,
      'page' => $page,
      'pageTemplate' => isset($pageTemplate) ? $pageTemplate : null,
    )) ?>
  <?php end_slot(); ?>

<?php endif; ?>

<?php $sf_user->setCulture($culture) ?>

<?php
if ($isUserAdmin && sfConfig::get('sf_cache') == true)
{
  sfPlop::set('sf_plop_cache', true);
  sfConfig::set('sf_cache', false);
}
?>

<?php include_partial('sfPlopCMS/slots', array(
  'slots' => $slots,
  'subSlots' => $subSlots,
  'culture' => $culture,
  'isUserAdmin' => $isUserAdmin,
  'page' => $page,
  'pageTemplate' => $pageTemplate
)) ?>
<?php echo clear(); ?>

<?php if ($isUserAdmin && sfPlop::get('sf_plop_cache') == true)
  sfConfig::set('sf_cache', true); ?>

<?php if (!$sf_user->isAuthenticated() && (sfPlop::get('sf_plop_use_statistics') == true)): ?>
  <?php echo sfPlop::get('sf_plop_statistics_code'); ?>
<?php endif; ?>