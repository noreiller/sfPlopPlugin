<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @see sfWidgetFormI18nChoiceLanguage 
 */
class sfWidgetFormPlopI18nChoiceLanguage extends sfWidgetFormChoice
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * culture:   The culture to use for internationalized strings
   *  * languages: An array of language codes to use
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
    $this->addOption('languages');
    $this->addOption('add_empty', false);

    parent::configure($options, $attributes);

    $this->setOption('choices', array());
  }

  public function getChoices()
  {
    // populate choices with all languages
    $culture = isset($this->options['culture']) ? $this->options['culture'] : 'en';

    $languages = sfCultureInfo::getInstance($culture)->getLanguages(
      isset($this->options['languages']) ? $this->options['languages'] : null
    );    

    foreach ($languages as $iso => $language)
    {
      if (!sfCultureInfo::validCulture($iso))
        unset($languages[$iso]);
    }

    $addEmpty = isset($this->options['add_empty']) ? $this->options['add_empty'] : false;
    if (false !== $addEmpty)
    {
      $languages = array('' => true === $addEmpty ? '' : $addEmpty) + $languages;
    }

    return $languages;
  }
}
