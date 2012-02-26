<?php 

class sfPlopWebResponse extends sfWebResponse {
	
  /**
   * Gets the current response content
   *
   * @return string Content
   */
  public function getContent()
  {
  	ProjectConfiguration::getActive()->loadHelpers(array('sfPlop'));

    return remove_whitespaces_from_html(parent::getContent());
  }

}

?>