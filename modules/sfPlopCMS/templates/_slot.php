<?php
if (!isset($isUserAdmin)) $isUserAdmin = null;
$isEditable = $isUserAdmin && $slot->isEditable();
$isTemplate = $isUserAdmin && $page->isTemplate();
$isPageArea = !$isTemplate && $page->getTemplate() && $page->getTemplate()->hasSlotArea();
$isArea = !$isTemplate && $isUserAdmin && $isSlotArea == true;
$withHandle = $isTemplate || ($isArea && !isset($slots)) || !$slot->getPage()->hasSlotArea();
$slotTemplateObject = $slot->getTemplateObject();
$classes = array($slot->getLayout(), $slot->getTemplate());
$slotConfig = isset($slotConfig)
  ? $slotConfig
  : sfPlopSlotConfigPeer::getOneByPageSlotAndCulture(
    (isset($pageTemplate) && !$slot->isEditable() && $slot->getId() == $pageTemplate->getId())
      ? $pageTemplate : $page,
    $slot,
    $culture,
    true
);
?>

<<?php echo html5Tag('section') ?>
  <?php if ($isUserAdmin): ?>id="slot_<?php echo $slot->getId() ?>"<?php endif; ?>
  class="section <?php echo implode(' ', $classes) ?>"
  <?php if ($withHandle): ?>data-handle-url="<?php echo url_for('@sf_plop_page_sort_slots?slug=' . $page->getSlug()) ?>"<?php endif; ?>
>
  <?php if (
    $isTemplate
    || ($isUserAdmin && !$isTemplate && $isPageArea && $slot->getPageId() == $page->getId())
    || ($isEditable && ($slot->isContentEditable() || $slot->isContentOptionable()))
  ): ?>
    <<?php echo html5Tag('header') ?> class="w-toolbar <?php echo $withHandle ? 'w w-handle' : null ?>">
       <?php include_partial('sfPlopCMS/toolbarSlot', array(
         'culture' => $culture,
         'slotConfig' => $slotConfig,
         'slot' => $slot,
         'page' => $page,
         'pageTemplate' => $pageTemplate,
         'isEditable' => $isEditable,
         'isTemplate' => $isTemplate
       )) ?>
    </<?php echo html5Tag('header') ?>>
  <?php endif; ?>

  <?php if ($isSlotArea && isset($slots)): ?>
    <?php include_partial('sfPlopCMS/slots', array(
        'slots' => $slots,
        'culture' => $culture,
        'isUserAdmin' => $isUserAdmin,
        'page' => $page,
        'pageTemplate' => $pageTemplate,
        'isSlotArea' => true
    )) ?>
  <?php else: ?>
    <div class="w-block content <?php echo $slotTemplateObject->getContentClasses(); ?>">
      <?php echo $slotTemplateObject->getSlotValue($slotConfig, array(
        'culture' => $culture,
        'page' => $page,
        'request_parameters' => $slot->getTemplateParameters($sf_request->getParameterHolder()->getAll())
      )) . "\n"; ?>
    </div>
  <?php endif; ?>
</<?php echo html5Tag('section') ?>>

