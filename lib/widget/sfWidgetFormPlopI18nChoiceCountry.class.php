<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @see sfWidgetFormI18nChoiceCountry 
 */
class sfWidgetFormPlopI18nChoiceCountry extends sfWidgetFormChoice
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * culture:   The culture to use for internationalized strings
   *  * countries: An array of country codes to use
   *  * add_empty: Whether to add a first empty value or not (false by default)
   *               If the option is not a Boolean, the value will be used as the text value
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormChoice
   */
  public function configure($options = array(), $attributes = array())
  {
    $this->addOption('culture');
    $this->addOption('countries');
    $this->addOption('add_empty', false);

    parent::configure($options, $attributes);

    $this->setOption('choices', array());
  }

  public function getChoices()
  {
    // populate choices with all countries
    $culture = isset($this->options['culture']) ? $this->options['culture'] : 'en';

    $countries = sfCultureInfo::getInstance($culture)->getCountries(
      isset($this->options['countries']) ? $this->options['countries'] : null
    );    

    foreach ($countries as $iso => $country)
    {
      if (!sfCultureInfo::validCulture(strtolower($iso)))
        unset($countries[$iso]);
    }

    $addEmpty = isset($this->options['add_empty']) ? $this->options['add_empty'] : false;
    if (false !== $addEmpty)
    {
      $countries = array('' => true === $addEmpty ? '' : $addEmpty) + $countries;
    }

    return $countries;
  }
}
