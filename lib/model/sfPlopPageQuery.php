<?php



/**
 * Skeleton subclass for performing query and update operations on the 'sf_plop_page' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.6.0-dev on:
 *
 * Fri Jan 28 18:11:39 2011
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.plugins.sfPlopPlugin.lib.model
 */
class sfPlopPageQuery extends BasesfPlopPageQuery {

  /**
   * Filter with published page.
   * @return sfPlopPageQuery
   */
  public function filterWithIsPublished()
	{
		return $this->filterByIsPublished(true);
	}

} // sfPlopPageQuery
