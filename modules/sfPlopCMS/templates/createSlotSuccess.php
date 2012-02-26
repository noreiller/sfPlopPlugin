<?php if (!isset($isSlotArea)) $isSlotArea = false; ?>
<?php include_partial('sfPlopCMS/slot', array(
  'culture' => $culture,
  'isUserAdmin' => $isUserAdmin,
  'slot' => $slot,
  'page' => $page,
  'pageTemplate' => isset($pageTemplate) ? $pageTemplate : null,
  'isSlotArea' => $isSlotArea
)) ?>