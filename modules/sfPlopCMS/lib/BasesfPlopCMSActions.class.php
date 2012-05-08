<?php

/**
 * DBasesfPlopCMSActions
 *
 * @author Aurélien MANCA <aurelien.manca@gmail.com>
 */
class BasesfPlopCMSActions extends sfActions
{

  const T_DEFAULT = 'default';

  /**
   * Retrieve the culture of the current user if available, otherwise redirect
   * to the default or preferred culture.
   * @return String
   */
  protected function getCulture()
  {
    $request = $this->getRequest();
    $culture = $this->getUser()->getCulture();
    $cultures = sfPlop::get('sf_plop_cultures');
    if (!in_array($culture, $cultures) || !$request->hasParameter('sf_culture'))
    {
      array_unshift($cultures, sfPlop::get('sf_plop_default_culture'));
      $culture = $request->getPreferredCulture($cultures);
      $this->getUser()->setCulture($culture);
      if ($request->hasParameter('sf_culture'))
        $this->forward('sfPlopCMS', 'index');
    }

    return $culture;
  }

  /**
   *
   * Adds a custom layout to the page.
   * @param String $template
   * @param String $layout
   */
  protected function decorate($template = null, $layout = 'layout')
  {
    if (!$this->getRequest()->isXmlHttpRequest())
      $this->setLayout (
        sfProjectConfiguration::getActive()
          ->getTemplateDir('sfPlopCMS', 'layout.php') . '/' . $layout
      );

    if ($template)
      $this->setTemplate($template);

    $this->getResponse()->addMeta('language', $this->getCulture(), true);

    if (!$this->getRequest()->isXmlHttpRequest() && $this->getUser()->isAuthenticated())
    {
      $this->getResponse()->addStylesheet('/sfPlopPlugin/css/admin.css', 'last');
      $this->getResponse()->addJavascript('/sfPlopPlugin/js/admin.js', 'last');
    }
    
    if (!$this->getRequest()->isXmlHttpRequest() && sfPlop::get('sf_plop_js') != '')
      $this->getResponse()->addJavascript(sfPlop::get('sf_plop_js'));

    return sfView::SUCCESS;
  }

  /**
   * Clear the template cache for the given slot
   * @param sfPlopSlot $slot
   * @param Integer $page_id
   */
  protected function clearTemplateCacheSlot(sfPlopSlot $slot, $page_id = null)
  {
    try {
      if ($cache = $this->getContext()->getViewCacheManager())
      {
        $flag = $cache->remove(
          '@sf_cache_partial?module=sfPlopCMS&action=_slot&sf_cache_key='
          . $slot->getCacheKey($this->culture, $page_id),
          '',
          'all'
        );
      }
    } catch (Exception $e) {
    }
  }

  public function preExecute()
  {
    $this->culture = $this->getCulture();
    $this->isUserAdmin = $this->getUser()->hasCredential('sf_plop_cms');
  }

  /**
   * Retrieve the root page and redirects to it.
   * @param sfWebRequest $request
   */
  function executeIndex(sfWebRequest $request)
  {
    $rootNode = sfPlopPageQuery::create()
      ->joinWithI18n($this->culture)
      ->findRoot();

    if (!$rootNode)
    {
      $rootNode = new sfPlopPage();
      $rootNode->setSlug('index');
      $rootNode->makeRoot();
      $rootNode->setCulture(sfPlop::get('sf_plop_default_culture'));
      $rootNode->setTitle('Page');
      $rootNode->save();
    }

    $this->redirect('@sf_plop_page_show?slug=' . $rootNode->getSlug());
  }

  /**
   * Show the page within the current culture.
   * @param sfWebRequest $request
   */
  function executeShow(sfWebRequest $request)
  {
    if ((sfPlop::get('sf_plop_private_access') == true) && !$this->getUser()->isAuthenticated())
      $this->forward(sfConfig::get('sf_login_module'), sfConfig::get('sf_login_action'));

    $this->page = $this->getRoute()->getObject();
    $this->forward404If(!$this->isUserAdmin && !$this->page->isPublished());
    $this->page->setCulture($this->culture);
    $this->forward404Unless($this->page);

    if ($this->page->isCategory() && !$this->isUserAdmin)
      $this->redirect('@sf_plop_page_show?slug=' . $this->page->getFirstChild()->getSlug());

    if ($this->page->isTemplate())
    {
      $tplId = $this->page->getId();
      $this->pageTemplate = null;
      $this->subSlots = array();
    }
    else
    {
      if (!$this->page->getTemplate()->isTemplate()
        && $this->page->getTemplate()->getTemplate()->hasSlotArea())
        $tplId = $this->page->getTemplate()->getTemplateId();
      else
        $tplId = $this->page->getTemplateId();

      $this->pageTemplate = sfPlopPageQuery::create()
        ->joinWithI18n($this->culture)
        ->findOneById($tplId);

      $this->subSlots = sfPlopSlotQuery::create()
        ->filterByPageId($tplId != $this->page->getTemplateId() ? $this->page->getTemplateId() : $this->page->getId())
        ->filterByIsPublished(true, $this->isUserAdmin ? Criteria::LESS_EQUAL : null)
        ->orderByRank('asc')
        ->groupById()
        ->find();
    }

    $this->slots = sfPlopSlotQuery::create()
      ->filterByPageId($tplId)
      ->filterByIsPublished(true, $this->isUserAdmin ? Criteria::LESS_EQUAL : null)
      ->orderByRank('asc')
      ->groupById()
      ->find();

    if (!$this->slots && $this->page->hasChildren())
      $this->redirect('@sf_plop_page_show?slug=' . $this->page->getFirstChild()->getSlug());
    elseif (!$this->slots)
      $this->redirect('@sf_plop_homepage');

    if ($this->page->getSeoDescription())
      $seo_desc = $this->page->getSeoDescription();
    else
      $seo_desc = sfPlop::get('sf_plop_website_description');

    if (sfPlop::get('sf_plop_use_title_in_seo_description') === true)
      $this->getResponse()->addMeta('description', $seo_desc . ', ' . $this->page->getTitle(), true);
    else
      $this->getResponse()->addMeta('description', $seo_desc, true);

    if ($this->page->getSeoKeywords())
      $this->getResponse()->addMeta('keywords', $this->page->getSeoKeywords(), true);
    else
      $this->getResponse()->addMeta('keywords', sfPlop::get('sf_plop_website_keywords'), true);

    if ($this->page->getSeoTitle())
      $this->getResponse()->setTitle($this->page->getSeoTitle(), true);
    elseif ($this->page->getTitle())
      $this->getResponse()->setTitle(sfPlop::setMetaTitle($this->page->getTitle()), true);

    $this->decorate();
  }

  /**
   * Create a new page.
   * @param sfWebRequest $request
   */
  function executeCreate(sfWebRequest $request)
  {
    $this->form = new sfPlopPageForm(null, array('culture' => $this->culture));

    if ($this->getRequest()->getMethod() == sfRequest::POST)
    {
      $values = $request->getParameter('sf_plop_page');
      $this->form->bind($values);
      if ($this->form->isValid())
      {
        $this->page = $this->form->save();
        if (!$request->isXmlHttpRequest())
          return $this->redirect('@sf_plop_page_show?slug=' . $this->page->getSlug());
        else {
          sfProjectConfiguration::getActive()->loadHelpers('Url');
          return $this->renderText(url_for('@sf_plop_page_show?slug=' . $this->page->getSlug()));
        }
      }
    }

    $this->params = array('form' => $this->form);
    $this->partial = 'create_page';

    $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Copy a page to a new one.
   * @param sfWebRequest $request
   */
  function executeCopy(sfWebRequest $request)
  {
    $this->page_ref = $this->getRoute()->getObject();
    $this->page_ref->setCulture($this->culture);
    $this->form = new sfPlopPageForm(null, array(
      'culture' => $this->culture,
      'page_ref' => $this->page_ref
    ));

    if ($this->getRequest()->getMethod() == sfRequest::POST)
    {
      $values = $request->getParameter('sf_plop_page');
      $this->form->bind($values);
      if ($this->form->isValid())
      {
        $this->page = $this->form->save();
        return $this->redirect('@sf_plop_page_show?slug=' . $this->page->getSlug());
      }
    }

    $this->params = array(
      'form' => $this->form,
      'page_ref' => $this->page_ref
    );
    $this->partial = 'copy_page';

    $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Copy a slot from a template page to another.
   * @param sfWebRequest $request
   */
  public function executeCopySlot(sfWebRequest $request)
  {
    $this->page_ref = $this->getRoute()->getObject();
    $this->page_ref->setCulture($this->culture);
    $this->slot_ref = sfPlopSlotPeer::retrieveByPK($request->getParameter('slot_id'));
    $this->forward404Unless($this->slot_ref);

    $this->form = new sfPlopSlotCopyForm(null, array(
      'page_ref' => $this->page_ref,
      'slot_ref' => $this->slot_ref
    ));

    if ($this->getRequest()->getMethod() == sfRequest::POST)
    {
      $values = $request->getParameter('sf_plop_slot');
      $this->form->bind($values);
      if ($this->form->isValid())
      {
        $this->slot = $this->form->save();

        return $this->redirect('@sf_plop_page_show?slug=' . $this->slot->getPage()->getSlug());
      }
    }

    $this->params = array(
      'form' => $this->form,
      'page_ref' => $this->page_ref,
      'slot_ref' => $this->slot_ref
    );
    $this->partial = 'copy_slot';

    $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Edit the page properties.
   * @param sfWebRequest $request
   */
  function executeEdit(sfWebRequest $request)
  {
    $this->page = $this->getRoute()->getObject();
    $this->page->setCulture($this->culture);
    $this->form = new sfPlopPageForm($this->page);

    if ($this->getRequest()->getMethod() == sfRequest::POST)
    {
      $values = $request->getParameter('sf_plop_page');
      $this->form->bind($values);
      if ($this->form->isValid())
      {
        $this->page = $this->form->save();
        if (!$request->isXmlHttpRequest())
          return $this->redirect('@sf_plop_page_show?slug=' . $this->page->getSlug());
        else
          return $this->page ? sfView::SUCCESS : sfView::ERROR;
      }
    }

    $this->partial = 'edit_page';
    $this->params = array('form' => $this->form);

    $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Edit the page attributes.
   * @param sfWebRequest $request
   */
  function executeEditAttributes(sfWebRequest $request)
  {
    $this->page = $this->getRoute()->getObject();
    $this->page->setCulture($this->culture);
    $this->form = new sfPlopPageAttributesForm($this->page);

    if ($this->getRequest()->getMethod() == sfRequest::POST)
    {
      $values = $request->getParameter('sf_plop_page');
      $this->form->bind($values);
      if ($this->form->isValid())
      {
        $this->page = $this->form->save();
        if (!$request->isXmlHttpRequest())
          return $this->redirect('@sf_plop_page_show?slug=' . $this->page->getSlug());
        else
          return $this->page ? sfView::SUCCESS : sfView::ERROR;
      }
    }

    $this->partial = 'edit_page_attributes';
    $this->params = array('form' => $this->form);

    $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Toggle the page publication.
   * @param sfWebRequest $request
   */
  public function executeTogglePublication(sfWebRequest $request)
  {
    $this->page = $this->getRoute()->getObject();
    $this->page->setIsPublished(!$this->page->isPublished());
    $flag = $this->page->save();

    if ($request->isXmlHttpRequest())
      return ($flag > 0) ? sfView::SUCCESS : sfView::ERROR;
    else
      $this->redirect($request->getReferer());
  }

  /**
   * Delete the page.
   * @param sfWebRequest $request
   */
  public function executeDelete(sfWebRequest $request)
  {
    $this->page = $this->getRoute()->getObject();

    if (!$this->page->isPublished())
      $this->page->delete();

    if ($this->page->isDeleted())
      $this->redirect('@sf_plop_homepage');
    else
      $this->redirect($request->getReferer());
  }

  /**
   * Sort the slots of a template page.
   * @param sfWebRequest $request
   */
  public function executeSortSlots(sfWebRequest $request)
  {
    $this->page = $this->getRoute()->getObject();
    $this->forward404Unless(
      $request->getMethod() == sfRequest::POST
      && (
        $this->page->isTemplate()
        || !$this->page->isTemplate() && $this->page->getTemplate() && $this->page->getTemplate()->hasSlotArea()
      )
    );
    $order = $request->getParameter('order', array());

    $is_valid = true;
    $objects = sfPlopSlotPeer::retrieveByPKs(array_keys($order));
    foreach ($objects as $object)
    {
      if ($object->getPageId() != $this->page->getId())
      {
        $is_valid = false;
        break;
      }
    }

    if ($is_valid)
      $reorder = sfPlopSlotPeer::reorder($order);
    else
      $reorder = false;

    if ($request->isXmlHttpRequest())
      return $reorder ? sfView::SUCCESS : sfView::ERROR;
    else
      $this->redirect($request->getReferer());
  }

  /**
   * Create a new slot for the given page.
   * @param sfWebRequest $request
   */
  function executeCreateSlot(sfWebRequest $request)
  {
    $this->page = $this->getRoute()->getObject();

    $this->form = new sfPlopSlotForm(null, array(
      'page_id' => $this->page->getId(),
      'unset_area' => (
        ($this->page->isTemplate() && $this->page->hasSlotArea())
        || ($this->page->isTemplate() && $this->page->getTemplateId() && $this->page->getTemplate()->hasSlotArea())
        || (!$this->page->isTemplate() && $this->page->getTemplate()->hasSlotArea())
      ) ? true : false
    ));

    if ($this->getRequest()->getMethod() == sfRequest::POST)
    {
      $values = $request->getParameter('sf_plop_slot');
      $this->form->bind($values);
      if ($this->form->isValid())
      {
        $this->slot = $this->form->save();
        if (!$request->isXmlHttpRequest())
          return $this->redirect('@sf_plop_page_show?slug=' . $this->page->getSlug());
        else
        {
          if (!$this->page->isTemplate())
            $this->pageTemplate = sfPlopPageQuery::create()
              ->joinWithI18n($this->culture)
              ->findOneById($this->page->getTemplateId());
          else
            $this->pageTemplate = null;

          $this->isSlotArea = $this->page->getTemplate() && $this->page->getTemplate()->hasSlotArea();

          return $this->slot ? sfView::SUCCESS : sfView::ERROR;
        }
      }
    }

    $this->params = array(
      'form' => $this->form,
      'page' => $this->page
    );
    $this->partial = 'create_slot';

    $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Add a slot area to the given page.
   * @param sfWebRequest $request
   */
  function executeAddSlotArea(sfWebRequest $request)
  {
    $this->page = $this->getRoute()->getObject();
    $this->form = new sfPlopSlotForm(null, array(
      'page_id' => $this->page->getId(),
      'is_area' => true
    ));

    if ($this->getRequest()->getMethod() == sfRequest::POST)
    {
      $values = $request->getParameter('sf_plop_slot');
      $this->form->bind($values);
      if ($this->form->isValid())
      {
        $this->slot = $this->form->save();
        if (!$request->isXmlHttpRequest())
          return $this->redirect('@sf_plop_page_show?slug=' . $this->page->getSlug());
        else
        {
          if (!$this->page->isTemplate())
            $this->pageTemplate = sfPlopPageQuery::create()
              ->joinWithI18n($this->culture)
              ->findOneById($this->page->getTemplateId());
          else
            $this->pageTemplate = null;
          return $this->slot ? sfView::SUCCESS : sfView::ERROR;
        }
      }
    }

    $this->params = array(
      'form' => $this->form,
      'page' => $this->page
    );
    $this->partial = 'add_slot_area';

    $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Edit the slot content and options.
   * @param sfWebRequest $request
   */
  function executeEditSlot(sfWebRequest $request)
  {
    $this->slotConfig = $this->getRoute()->getObject();
    $this->forward404Unless(
      $this->slotConfig->getSlot()->getTemplateObject()->isContentEditable()
      || $this->slotConfig->getSlot()->getTemplateObject()->isContentOptionable()
    );

    $this->slotConfig->setCulture($this->culture);

    $this->form = new sfPlopSlotConfigForm($this->slotConfig, array(
      'culture' => $this->culture,
      'isAjax' => $request->isXmlHttpRequest()
    ));

    if ($this->getRequest()->getMethod() == sfRequest::POST)
    {
      $values = $request->getParameter('sf_plop_slot_config');
      $this->form->bind($values);
      if ($this->form->isValid())
      {
        $this->slotConfig = $this->form->save();
        $this->page = $this->slotConfig->getPage();

        $this->clearTemplateCacheSlot($this->slotConfig->getSlot(), $this->page->getId());

        if (!$request->isXmlHttpRequest())
          return $this->redirect('@sf_plop_page_show?slug=' . $this->page->getSlug());
        else
        {
          $this->slot = $this->slotConfig->getSlot();
          $this->slotConfig->clean();
          if (!$this->page->isTemplate())
            $this->pageTemplate = sfPlopPageQuery::create()
              ->joinWithI18n($this->culture)
              ->findOneById($this->page->getTemplateId());
          else
            $this->pageTemplate = null;
          return $this->slotConfig ? sfView::SUCCESS : sfView::ERROR;
        }
      }
    }

    $this->params = array('form' => $this->form);
    $this->partial = 'edit_slot';

    $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Edit the slot options.
   * @param sfWebRequest $request
   */
  public function executeEditSlotOptions(sfWebRequest $request)
  {
    $this->slot = $this->getRoute()->getObject();
    $this->form = new sfPlopSlotForm($this->slot, array(
      'isAjax' => $request->isXmlHttpRequest()
    ));

    if ($this->getRequest()->getMethod() == sfRequest::POST)
    {
      $values = $request->getParameter('sf_plop_slot');
      $this->form->bind($values);
      if ($this->form->isValid())
      {
        $this->slot = $this->form->save();
        $this->page = $this->slot->getPage();

        $this->clearTemplateCacheSlot($this->slot);

        if (!$request->isXmlHttpRequest())
          return $this->redirect('@sf_plop_page_show?slug=' . $this->page->getSlug());
        else {
          if (!$this->page->isTemplate())
            $this->pageTemplate = sfPlopPageQuery::create()
              ->joinWithI18n($this->culture)
              ->findOneById($this->page->getTemplateId());
          else
            $this->pageTemplate = null;
          return $this->slot ? sfView::SUCCESS : sfView::ERROR;
        }
      }
    }

    $this->params = array('form' => $this->form);
    $this->partial = 'edit_slot_options';

    $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Toggle the slot publication.
   * @param sfWebRequest $request
   */
  public function executeToggleSlotPublication(sfWebRequest $request)
  {
    $this->slot = $this->getRoute()->getObject();
    $this->slot->setIsPublished(!$this->slot->isPublished());
    $flag = $this->slot->save();

    $this->clearTemplateCacheSlot($this->slot);

    if ($request->isXmlHttpRequest())
      return ($flag > 0) ? sfView::SUCCESS : sfView::ERROR;
    else
      $this->redirect($request->getReferer());
  }

  /**
   * Toggle the slot edition.
   * @param sfWebRequest $request
   */
  public function executeToggleSlotEdition(sfWebRequest $request)
  {
    $this->slot = $this->getRoute()->getObject();
    $this->slot->setIsEditable(!$this->slot->isEditable());
    $flag = $this->slot->save();

    $this->clearTemplateCacheSlot($this->slot);

    if ($request->isXmlHttpRequest())
      return ($flag > 0) ? sfView::SUCCESS : sfView::ERROR;
    else
      $this->redirect($request->getReferer());
  }

  /**
   * Move the slot up or down.
   * @param sfWebRequest $request
   */
  public function executeMoveSlot(sfWebRequest $request)
  {
    $this->slot = $this->getRoute()->getObject();
    $this->direction = $request->getParameter('direction');

    if ($this->direction == 'up')
      $this->slot = $this->slot->moveUp();
    elseif ($this->direction == 'down')
      $this->slot = $this->slot->moveDown();

    if ($request->isXmlHttpRequest())
      return $this->slot ? sfView::SUCCESS : sfView::ERROR;
    else
      $this->redirect($request->getReferer());
  }

  /**
   * Reset the slot.
   * @param sfWebRequest $request
   */
  public function executeResetSlot(sfWebRequest $request)
  {
    $this->slotConfig = $this->getRoute()->getObject();
    $flag = $this->slotConfig->reset($this->culture);

    if ($request->isXmlHttpRequest())
    {
      $this->slot = $this->slotConfig->getSlot();
      $this->page = $this->slotConfig->getPage($this->culture);

      $this->slotConfig->clean();
      $this->clearTemplateCacheSlot($this->slot);

      if (!$this->page->isTemplate())
        $this->pageTemplate = sfPlopPageQuery::create()
          ->joinWithI18n($this->culture)
          ->findOneById($this->page->getTemplateId());
      else
        $this->pageTemplate = null;

      return ($flag > 0) ?  sfView::SUCCESS : sfView::ERROR;
    }
    else
      $this->redirect($request->getReferer());
  }

  /**
   * Delete the slot.
   * @param sfWebRequest $request
   */
  public function executeDeleteSlot(sfWebRequest $request)
  {
    $this->slot = $this->getRoute()->getObject();
    if (!$this->slot->isPublished())
      $this->slot->delete();

    if ($request->isXmlHttpRequest())
      return $this->slot->isDeleted() ? sfView::SUCCESS : sfView::ERROR;
    else
      $this->redirect($request->getReferer());
  }

  /**
   * Retrieve the slot admin items.
   * @param sfWebRequest $request
   */
  public function executeSlotToolbar(sfWebRequest $request)
  {
    $slotConfig = $this->getRoute()->getObject();
    $this->forward404Unless($slotConfig);

    $slot = $slotConfig->getSlot();
    $page = $slotConfig->getPage($this->culture);

    $isTemplate = $this->isUserAdmin && (
      $page->isTemplate() || !$slot->getPage()->hasSlotArea()
    );
    $isEditable = $this->isUserAdmin && $slot->isEditable();

    $this->params = array(
      'culture' => $this->culture,
      'slot' => $slot,
      'slotConfig' => $slotConfig,
      'page' => $page,
      'isEditable' => $isEditable,
      'isTemplate' => $isTemplate
    );
    $this->partial = 'toolbarSlotItems';

    if ($request->isXmlHttpRequest())
    {
      $this->setLayout(false);
      sfProjectConfiguration::getActive()->LoadHelpers('Partial');
      return $this->renderText(get_partial($this->partial, $this->params));
    }
    else
    {
      return $this->decorate(sfPlopCMSActions::T_DEFAULT);
    }
  }

  /**
   * Display the 404 error default template.
   * @param sfWebRequest $request
   */
  public function executeError404(sfWebRequest $request)
  {
    $this->partial = 'error404';
    $this->getResponse()->setStatusCode(404);

    ProjectConfiguration::getActive()->LoadHelpers(array('I18N'));
    $this->getResponse()->setTitle(sfPlop::setMetaTitle(__('Error 404')));

    return $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Function which aims to bind a contact form without rendering the view
   * @param sfWebRequest $request
   */
  public function executeContact(sfWebRequest $request)
  {
    $user = $this->getUser();
    $isAjax = $request->isXmlHttpRequest();
    $referer = $request->getParameter('r');

    if ($request->getMethod() == sfRequest::POST)
    {
      $slot = sfPlopSlotConfigPeer::retrieveByPk($request->getParameter('c'));
      $form = new sfPlopPublicContactForm(null, array(
        'contact' => $slot
          ? $slot->getOption('contact', null, $this->culture)
          : null
        )
      );

      $form->bind($request->getParameter('contact'));
      $user->setAttribute('contactForm_valid', false);
      $user->setAttribute('contactForm_form', $form);
      if ($form->isValid())
      {
        $mailer = new sfPlopMessaging($form->getValues());
        $return = $mailer->sendEmail($this->getMailer());

        if ($return['count'] == $return['sent'])
//        {
          $user->setAttribute('contactForm_valid', true);
//        }
//        else
//        {
//        }
      }
//      else
//      {
//        $user->setAttribute('contactForm_valid', false);
//        $user->setAttribute('contactForm_form', $form);
//      }
    }

    return $this->redirect('@sf_plop_page_show?slug=' . $referer);
  }

  /**
   * Update the profile theme
   * @param sfWebRequest $request
   * @return String
   */
  public function executeProfileTheme(sfWebRequest $request)
  {
    $theme = $request->getParameter('theme');
    $return = false;
    $this->forward404Unless($request->isMethod(sfRequest::POST) && $theme);

    if (in_array($theme, array_keys(sfPlop::get('sf_plop_loaded_admin_themes'))))
    {
      $profile = $this->getUser()->getProfile();
      $profile->setTheme($theme);
      $profile->save();
      $return = true;
    }

    if ($request->isXmlHttpRequest())
      $this->setLayout(false);
    else
      $this->redirect($request->getReferer());

    $this->theme = $theme;

    if ($return === false)
      return sfView::ERROR;
  }


  /**
   * Display the user signin form.
   * @param sfWebRequest $request
   */
  public function executeSignin(sfWebRequest $request)
  {
    $user = $this->getUser();

    if ($user->isAuthenticated())
      $this->redirect('@sf_plop_homepage');

    if ($request->isXmlHttpRequest())
    {
      $this->getResponse()->setHeaderOnly(true);
      $this->getResponse()->setStatusCode(401);

      return sfView::NONE;
    }

    $class = sfConfig::get('app_sf_guard_plugin_signin_form', 'sfGuardFormSignin');
    $this->form = new $class();

    $url = $request->getParameter('url');

    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request->getParameter('signin'));
      if ($this->form->isValid())
      {
        $values = $this->form->getValues();
        $this->getUser()->signIn($values['user'], array_key_exists('remember', $values) ? $values['remember'] : false);

        $referer = $request->getReferer() != '' ? $request->getReferer() : $user->getReferer('@sf_plop_homepage');
        $signinUrl = sfConfig::get('app_sf_guard_plugin_success_signin_url', $referer);

        return $this->redirect($url ? $url : $signinUrl);
      }
    }

    $user->setReferer($this->getContext()->getActionStack()->getSize() > 1 ? $request->getUri() : $request->getReferer());

    $this->partial = 'login';
    $this->params = array(
      'form' => $this->form,
      'url_suffix' => $url ? '?url=' . $url : null
    );

    $this->getResponse()->setStatusCode(401);

    ProjectConfiguration::getActive()->LoadHelpers(array('I18N'));
    $this->getResponse()->setTitle(sfPlop::setMetaTitle(__('Login')));

    $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Secure
   * @param sfWebRequest $request
   */
  public function executeSecure(sfWebRequest $request)
  {
    $this->partial = 'secure';
    $this->getResponse()->setStatusCode(404);

    ProjectConfiguration::getActive()->LoadHelpers(array('I18N'));
    $this->getResponse()->setTitle(sfPlop::setMetaTitle(__('Security')));

    $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Registration
   * @param sfWebRequest $request
   */
  public function executeRegisterShortcut(sfWebRequest $request)
  {
    if (sfPlop::get('sf_plop_allow_registration') != true)
      $this->redirect(sfConfig::get('sf_login_module') . '/' . sfConfig::get('sf_login_action'));
    else
      $this->redirect('@sf_plop_register');
  }

  /**
   * Registration
   * @param sfWebRequest $request
   */
  public function executeRegister(sfWebRequest $request)
  {
    if (sfPlop::get('sf_plop_allow_registration') != true)
      $this->redirect(sfConfig::get('sf_login_module') . '/' . sfConfig::get('sf_login_action'));

    $user = $this->getUser();

    if ($user->isAuthenticated())
      $this->redirect('@sf_plop_homepage');

    $this->form = new sfPlopGuardProfileRegisterForm(null, array(
      'user_culture' => $this->culture,
      'culture' => $this->culture
    ));

    $url = $request->getParameter('url');

    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request->getParameter('sf_guard_user'));

      if ($this->form->isValid())
      {
        $sf_guard_user = $this->form->save();
        $sf_guard_user->addGroupByName('users');
        $this->getUser()->signIn($sf_guard_user);

        $referer = $request->getReferer() != '' ? $request->getReferer() : $user->getReferer('@sf_plop_homepage');
        $signinUrl = sfConfig::get('app_sf_guard_plugin_success_signin_url', $referer);

        $this->redirect(isset($url) ? $url : 'signinUrl');
      }
    }

    $this->partial = 'register';
    $this->params = array(
      'form' => $this->form,
      'format' => 'wide',
      'url_suffix' => $url ? '?url=' . $url : null
    );

    ProjectConfiguration::getActive()->LoadHelpers(array('I18N'));
    $this->getResponse()->setTitle(sfPlop::setMetaTitle(__('Register')));

    $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Unregistration
   * @param sfWebRequest $request
   */
  public function executeUnregister(sfWebRequest $request)
  {
    if (sfPlop::get('sf_plop_allow_registration') != true)
      $this->redirect(sfConfig::get('sf_login_module') . '/' . sfConfig::get('sf_login_action'));

    if (!$request->getParameter('confirmation'))
      $this->partial = 'unregister';

    if ($request->getParameter('confirmation'))
    {
      $this->getUser()->getGuardUser()->delete();
      $this->getUser()->signOut();
      $this->redirect('@sf_plop_homepage');
    }
    ProjectConfiguration::getActive()->LoadHelpers(array('I18N'));
    $this->getResponse()->setTitle(sfPlop::setMetaTitle(__('Unregistration', '', 'plopAdmin')));

    $this->decorate(sfPlopCMSActions::T_DEFAULT);
  }

  /**
   * Generate a sitemap of the website
   * @param sfWebRequest $request
   */
  public function executeSitemap(sfWebRequest $request)
  {
    $gsg = new GsgXml();

    $gsg->addUrl('http://' . $_SERVER['HTTP_HOST'], false, date('c'), false, 'daily', 1);

    $pages = sfPlopPageQuery::create()
      ->filterByIsPublished(true)
      ->filterByIsCategory(false)
      ->find()
    ;
    foreach ($pages as $page)
    {
      $url = '@sf_plop_page_show?slug=' . $page->getSlug();
      $url_date = $page->getUpdatedAt('c');
      foreach (sfPlop::get('sf_plop_cultures') as $culture)
        $gsg->addUrl(
          $this->getController()->genUrl($url . '&sf_culture=' . $culture, true),
          false,
          $url_date,
          false,
          'daily',
          0.5
        );
    }

    $gsg->generateXml();

    $this->setLayout(false);
    $this->getResponse()->setContentType('text/xml');

    return $this->renderText($gsg->output());
  }

  /**
   * Retrieve the assets files and/or folders
   * @param sfWebRequest $request
   * @return Json array
   */
  public function executeWsRepository(sfWebRequest $request)
  {
    if (!$this->isUserAdmin)
      return;

    $data = array();
    $type = strtolower($request->getParameter('type', 'folder'));
    $folder = strtolower($request->getParameter('folder', null));
    $term = strtolower($request->getParameter('term', null));

    if ($type == 'folder' && !$folder)
    {
      $data ['Assets']= 'Assets';
      $data ['Links']= 'Links';
    }
    elseif ($type == 'file')
    {

      if ($folder == '/links')
      {
        // Links
        $page_query = sfPlopPageQuery::create();
        if ($term)
          $page_query->filterBySlug('%' . $term . '%', Criteria::LIKE);
        $nodes = sfPlopPageQuery::create()
          ->findRoot()
          ->getBranch($page_query)
        ;

        sfProjectConfiguration::getActive()->loadHelpers('Url');
        foreach($nodes as $node)
        {
          $node->setCulture($this->getCulture());
          $url = url_for('@sf_plop_page_show?slug=' . $node->getSlug());
          $data []= array(
            'name' => $node->getTitle(),
            'description' => $node->getTitle(),
            'title' => $node->getTitle(),
            'forward' => $url,
            'url' => $url,
            'type' => 'website',
            'html' => '<a href="' . $url . '">' . $node->getTitle() . '</a>'
          );
        }
      }
      elseif ($folder == '/assets')
      {
        if (in_array('sfAssetLibrary', array_keys(sfPlop::getSafePluginModules())))
        {
          // Assets
          $asset_query = sfAssetQuery::create();
          if ($term)
            $asset_query->filterByFilename('%' . $term . '%', Criteria::LIKE);
          $assets = $asset_query->find();
          foreach($assets as $asset)
          {
            $data []= array(
              'name' => $asset->getFilename(),
              'title' => $asset->getFilename(),
              'url' => $asset->getUrl(),
              'src' => $asset->getUrl(),
              'type' => $asset->getType(),
              'html' => '<a href="' . $asset->getUrl() . '">'
                . '<img src="' . $asset->getUrl() . '" height="25" />'
                . $asset->getFilename()
                .'</a>'
            );
          }
        }
      }
    }

    $this->getResponse()->setContentType('text/json');

    return $this->renderText(str_replace('\\/', '/', json_encode($data)));
  }

  /**
   * Get or set a value thanks to the given key.
   * @param sfWebRequest $request
   * @return String
   */
  public function executeWsConfig(sfWebRequest $request)
  {
    $key = $request->getParameter('key');
    $value = $request->getParameter('value');
    $return = false;
    $this->forward404Unless($request->isXmlHttpRequest() && $key && $this->isUserAdmin);

    if ($request->isMethod($request::POST))
    {
      $this->forward404Unless($value);
      sfPlopConfigPeer::addOrUpdate($key, $value);
      $return = true;
    }
    elseif ($request->isMethod($request::GET))
    {
      $return = sfPlop::get($key);
    }

    $this->setLayout(false);
    $this->key = $key;

    if (!is_bool($return))
      return $this->renderText($return);
    elseif ($return === false)
      return sfView::ERROR;
  }

  public function executeEditTheme(sfWebRequest $request)
  {
    $this->form = new sfPlopThemeEditionForm();
    
    if ($request->getMethod() == sfRequest::POST)
    {
      $this->return = false;
      $this->form->bind($request->getParameter('sfPlopThemeEdition'));
      if ($this->form->isValid()) 
      {
        $this->form->save();
        $this->return = true;

        if ($request->isXmlHttpRequest())
          return $this->return ? sfView::SUCCESS : sfView::ERROR;
        else
          return $this->redirect($request->getReferer());
      }
    }
    else
    {
      $this->partial = 'edit_theme';
      $this->params = array('form' => $this->form);
      ProjectConfiguration::getActive()->LoadHelpers(array('I18N'));
      $this->getResponse()->setTitle(sfPlop::setMetaTitle(__('Edit theme', '', 'plopAdmin')));

      $this->decorate(sfPlopCMSActions::T_DEFAULT);
    }
  }

  public function executeStylesheet(sfWebRequest $request)
  {
    // Layout
    $this->layoutL = sfPlop::get('sf_plop_layout_block_left');
    $this->layoutC = sfPlop::get('sf_plop_layout_block_center');
    $this->layoutR = sfPlop::get('sf_plop_layout_block_right');
    $this->layoutLC = sfPlop::get('sf_plop_layout_block_left_center');
    $this->layoutCR = sfPlop::get('sf_plop_layout_block_center_right');

    // Theme
    $this->bgClr = sfPlop::get('sf_plop_background_color');
    $this->bgImg = sfPlop::get('sf_plop_background_image');
    $this->bgRpt = sfPlop::get('sf_plop_background_repeat');
    $this->bgPsH = sfPlop::get('sf_plop_background_position_horizontal');
    $this->bgPsV = sfPlop::get('sf_plop_background_position_vertical');
    $this->blckBgClr = sfPlop::get('sf_plop_block_background_color');
    $this->blckBgImg = sfPlop::get('sf_plop_block_background_image');
    $this->blckBgRpt = sfPlop::get('sf_plop_block_background_repeat');
    $this->blckBgPsH = sfPlop::get('sf_plop_block_background_position_horizontal');
    $this->blckBgPsV = sfPlop::get('sf_plop_block_background_position_vertical');
    $this->blckBrdrClr = sfPlop::get('sf_plop_block_border_color');
    $this->blckBrdrSz = sfPlop::get('sf_plop_block_border_size');
    $this->blckBrdrStl = sfPlop::get('sf_plop_block_border_style');
    $this->blckBldClr = sfPlop::get('sf_plop_block_bold_color');
    $this->blckItlcClr = sfPlop::get('sf_plop_block_italic_color');
    $this->blckLnkClr = sfPlop::get('sf_plop_block_link_color');
    $this->blckTxtClr = sfPlop::get('sf_plop_block_text_color');
    $this->blckTxtFnt = sfPlop::get('sf_plop_block_text_font');
    $this->blckTxtSz = sfPlop::get('sf_plop_block_text_size');
    $this->wrpBgClr = sfPlop::get('sf_plop_wrapper_background_color');
    $this->wrpBgImg = sfPlop::get('sf_plop_wrapper_background_image');
    $this->wrpBgRpt = sfPlop::get('sf_plop_wrapper_background_repeat');
    $this->wrpBgPsH = sfPlop::get('sf_plop_wrapper_background_position_horizontal');
    $this->wrpBgPsV = sfPlop::get('sf_plop_wrapper_background_position_vertical');
    $this->wrpBrdrClr = sfPlop::get('sf_plop_wrapper_border_color');
    $this->wrpBrdrSz = sfPlop::get('sf_plop_wrapper_border_size');
    $this->wrpBrdrStl = sfPlop::get('sf_plop_wrapper_border_style');
    if ($this->getUser()->isAuthenticated() && ($this->bgPsV == 'top'))
    {
      $this->bgPsV = '75px';
    }

    $this->setLayout(false);
    $this->getResponse()->setContentType('text/css');
  }

}

?>
