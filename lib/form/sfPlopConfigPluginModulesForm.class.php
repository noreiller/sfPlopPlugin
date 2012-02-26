<?php

/**
 * sfPlopConfigPluginModules form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfPlopConfigPluginModulesForm extends sfPlopConfigForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'sf_plop_enabled_modules' => new sfWidgetFormChoice(array(
        'multiple' => true,
        'expanded' => true,
        'choices' => $this->getPluginModules(),
        'default' => sfPlop::get('sf_plop_enabled_modules'),
        'label' => 'Enabled modules'
      )),
      'sf_plop_enabled_links' => new sfWidgetFormChoice(array(
        'multiple' => true,
        'expanded' => true,
        'choices' => $this->getPluginLinks(),
        'default' => sfPlop::get('sf_plop_enabled_links'),
        'label' => 'Enabled links'
      ))
    ));

    $this->setValidators(array(
      'sf_plop_enabled_modules' => new sfValidatorChoice(array(
        'required' => false,
        'multiple' => true,
        'choices' => array_keys($this->getPluginModules())
      )),
      'sf_plop_enabled_links' => new sfValidatorChoice(array(
        'required' => false,
        'multiple' => true,
        'choices' => array_keys($this->getPluginLinks())
      ))
    ));

    parent::configure();
  }

  protected function getPluginModules()
  {
    $exceptions = array('sf_plop_cms', 'sf_plop_dashboard');
    $return = array();
    $modules = sfPlop::getSafePluginModules(true);

    foreach ($modules as $module => $infos)
      if (!in_array($module, $exceptions))
        $return[$module] = $infos['name'];

    return $return;
  }

  protected function getPluginLinks()
  {
    $exceptions = array();
    $return = array();
    $links = sfPlop::getSafePluginLinks(true);

    foreach ($links as $link => $infos)
      if (!in_array($link, $exceptions))
        $return[$link] = $infos['name'];

    return $return;
  }
}
