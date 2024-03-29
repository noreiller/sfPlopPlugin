<?php



/**
 * Skeleton subclass for performing query and update operations on the 'sf_plop_slot_config' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.6.0-dev on:
 *
 * Thu Feb  3 08:23:10 2011
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.plugins.sfPlopPlugin.lib.model
 */
class sfPlopSlotConfigQuery extends BasesfPlopSlotConfigQuery {

  /**
   * Filter the slot config by an equal slot id and a not equal page id.
   * @param Int $slot_id
   * @param Int $page_id
   * @return sfPlopSlotConfigQuery
   */
  public function retrieveDescendants($slot_id = null, $page_id = null)
  {
    if (!$slot_id || !$page_id)
      return;

    return $this
      ->filterBySlotId($slot_id)
      ->filterByPageId($page_id, Criteria::NOT_EQUAL)
    ;
  }

} // sfPlopSlotConfigQuery
