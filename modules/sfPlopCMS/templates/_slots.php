<?php if (!isset($isSlotArea)) $isSlotArea = false; ?>
<?php foreach ($slots as $slot): ?>
  <?php if ($slot->isTemplateLoaded()): ?>
    <?php if ($slot->getTemplate() == 'Area'): ?>
      <?php if ($isUserAdmin && $page->hasSlotArea()): ?>
        <?php include_partial('sfPlopCMS/slot', array(
          'culture' => $culture,
          'isUserAdmin' => $isUserAdmin,
          'slot' => $slot,
          'page' => $page,
          'pageTemplate' => isset($pageTemplate) ? $pageTemplate : null,
          'isSlotArea' => $isSlotArea
        )) ?>
      <?php elseif ($isUserAdmin): ?>
        <<?php echo html5Tag('section') ?> id="slot_<?php echo $slot->getId() ?>" class="section <?php echo implode(' ', array($slot->getLayout(), $slot->getTemplate())) ?>">
        </<?php echo html5Tag('section') ?>>
      <?php endif; ?>
      <?php include_partial('sfPlopCMS/slots', array(
          'slots' => $subSlots,
          'culture' => $culture,
          'isUserAdmin' => $isUserAdmin,
          'page' => $page,
          'pageTemplate' => $pageTemplate,
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