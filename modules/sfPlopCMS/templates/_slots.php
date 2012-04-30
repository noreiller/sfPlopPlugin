<?php if (!isset($isSlotArea)) $isSlotArea = false; ?>
<?php foreach ($slots as $slot): ?>
  <?php if ($slot->isTemplateLoaded()): ?>
    <?php if ($slot->getTemplate() == 'Area'): ?>
      <?php include_partial('sfPlopCMS/slot', array(
        'culture' => $culture,
        'isUserAdmin' => $isUserAdmin,
        'slot' => $slot,
        'slots' => $subSlots,
        'page' => $page,
        'pageTemplate' => isset($pageTemplate) ? $pageTemplate : null,
        'isSlotArea' => true
      )) ?>
    <?php else: ?>
      <?php $cache_key = 'plop_slot_' . $page->getId() . '_' . $slot->getId() . '_' . $culture
        . $slot->getStringifiedTemplateParameters($sf_request->getParameterHolder()->getAll(), '_'); ?>
      <?php if (in_array($slot->getTemplate(), sfPlop::get('sf_plop_uncached_slots')))
        $cache_key .= ('_' . time()); ?>
      <?php include_partial('sfPlopCMS/slot', array(
        'culture' => $culture,
        'isUserAdmin' => $isUserAdmin,
        'slot' => $slot,
        'page' => $page,
        'pageTemplate' => isset($pageTemplate) ? $pageTemplate : null,
        'sf_cache_key' => $cache_key,
        'isSlotArea' => $isSlotArea
      )) ?>
    <?php endif; ?>
  <?php endif; ?>
<?php endforeach; ?>