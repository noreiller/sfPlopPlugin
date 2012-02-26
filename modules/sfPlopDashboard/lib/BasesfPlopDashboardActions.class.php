<?php

class BasesfPlopDashboardActions extends sfActions
{
  /**
   * Check admin credentials.
   * @return Boolean
   */
  protected function checkCredentials()
  {
    $module = 'sf_plop_dashboard';
    if (!in_array($module, array_keys(sfPlop::getSafePluginModules()))
      && !$this->getUser()->hasCredential($module)
    )
      $this->forward404();

    if (!$this->getUser()->isAuthenticated())
      $this->forward(sfConfig::get('sf_login_module'), sfConfig::get('sf_login_action'));

    if (!$this->getUser()->hasCredential($module))
      $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));

    return $this->getUser()->isAuthenticated() && $this->getUser()->isSuperAdmin();
  }

  public function preExecute()
  {
    $this->isUserAdmin = $this->checkCredentials();
    $this->tabs = sfPlop::get('sf_plop_dashboard_settings_tabs');
    $this->sub = $this->getRequest()->getParameter('sub');

    ProjectConfiguration::getActive()->LoadHelpers(array('I18N'));
    $this->getResponse()->setTitle(sfPlop::setMetaTitle(__('Dashboard', '', 'plopAdmin')));
  }

  public function executeIndex()
  {
    $this->version = sfPlop::getVersion();
  }

  /**
   *
   * @param sfWebRequest $request
   */
  public function executeThemes(sfWebRequest $request)
  {
    $this->tmp_theme = null;
    $this->use_theme = null;
    $this->theme = sfPlop::get('sf_plop_theme');
    $values = $request->getParameter('sfPlopConfig');
    $this->form = new sfPlopConfigThemeForm();

    if ($this->getRequest()->getMethod() == sfRequest::POST)
    {
      $values = $request->getParameter('sfPlopConfig');
      $this->form->bind($values);
      $this->getUser()->setFlash('form.saved', false);

      if ($this->form->isValid())
      {
        if (isset($values['sf_plop_theme_preview']) && $values['sf_plop_theme_preview'] == true)
        {
          $this->use_theme = $values['sf_plop_theme'];
          $this->tmp_theme = $values['sf_plop_theme'];
        }
        else
        {
          $this->form->save();
          $this->getUser()->setFlash('form.saved', true);
          $this->redirect('@sf_plop_dashboard_themes');
        }
      }
    }
  }

  /**
   *
   * @param sfWebRequest $request
   */
  public function executeSettings(sfWebRequest $request)
  {
    $method = 'sfPlopConfig' . ucfirst($this->sub) . 'Form';
    if (($this->sub != '') && isset($this->tabs[$this->sub]) && class_exists($method))
    {
      $this->form = new $method(array(), array('user' => $this->getUser()));
      if ($this->getRequest()->getMethod() == sfRequest::POST)
      {
        $values = $request->getParameter('sfPlopConfig');
        $this->form->bind($values);
        $this->getUser()->setFlash('form.saved', false);

        if ($this->form->isValid())
        {
          $this->form->save();
          $this->getUser()->setFlash('form.saved', true);
          $this->redirect('@sf_plop_dashboard_settings?sub=' . $this->sub);
        }
      }
    }
  }

  public function executeEmptyCache(sfWebRequest $request)
  {
    sfPlop::emptyCache();
    if (!$this->getRequest()->isXmlHttpRequest())
      $this->redirect($request->getReferer());
  }


  public function executePermissions(sfWebRequest $request)
  {
    $module = 'sfGuardUser';
    if (!in_array($module, array_keys(sfPlop::getSafePluginModules())))
      $this->redirect('@sf_plop_dashboard');

    if ($request->isMethod(sfRequest::POST))
    {
      if ($request->isXmlHttpRequest())
      {
        $this->setTemplate('ajaxPermissions');
        $this->setLayout(false);
      }

      $group_id = $request->getParameter('g');
      $user_id = $request->getParameter('u');
      $permission_id = $request->getParameter('p');

      if ($group_id)
      {
        $group_exists = sfPlopGuard::groupExists($group_id);
        if (!$group_exists && $request->isXmlHttpRequest())
          return sfView::ERROR;
        else if (!$group_exists)
          $this->redirect('@sf_plop_dashboard_permissions');
      }

      if ($user_id)
      {
        $user_exists = sfPlopGuard::userExists($user_id);
        if (!$user_exists && $request->isXmlHttpRequest())
          return sfView::ERROR;
        else if (!$user_exists)
          $this->redirect('@sf_plop_dashboard_permissions');
      }

      if (isset($group_exists) && isset($user_exists))
      {
        $user_group = sfGuardUserGroupPeer::retrieveByPK($user_id, $group_id);
        if ($user_group)
        {
          $user_group->delete();
        }
        else
        {
          $user_group = new sfGuardUsergroup();
          $user_group->setUserId($user_id);
          $user_group->setGroupId($group_id);
          $user_group->save();
          $this->getResponse()->setStatusCode(201);
        }
      }


      if ($permission_id)
      {
        if ($permission_id == 'super')
        {
          if (!sfPlopGuard::isLastSuperAdminUser($user_id))
          {
            $user = sfGuardUserPeer::retrieveByPK($user_id);
            if ($user->getIsSuperAdmin())
              $user->setIsSuperAdmin(false);
            else
              $user->setIsSuperAdmin(true);
            $user->save();
          }
          else
          {
            $this->getResponse()->setStatusCode(202);
            return sfView::ERROR;
          }
        }
        else
        {
          if (!is_int($permission_id))
          {
            $permission_exists = sfPlopGuard::permissionExists($permission_id);
            if (!$permission_exists)
            {
              $modules = sfPlop::getSafePluginModules();
              if ($request->isXmlHttpRequest() && !isset($modules[$permission_id]))
                return sfView::ERROR;
              elseif (!isset($modules[$permission_id]))
                $this->redirect('@sf_plop_dashboard_permissions');
              else
                $module = $modules[$permission_id];

              $permission = new sfGuardPermission();
              $permission->setName($permission_id);
              $permission->setDescription($module['name']);
              $permission->save();

              $permission_id = $permission->getId();
              $this->getResponse()->setStatusCode(201);
            }
            else
            {
              $permission_id = sfPlopGuard::getPermission($permission_id)->getId();
            }
          }
          else
          {
            $permission_exists = sfPlopGuard::permissionExists($permission_id);
            if (!$permission_exists && $request->isXmlHttpRequest())
              return sfView::ERROR;
            else if (!$permission_exists)
              $this->redirect('@sf_plop_dashboard_permissions');
          }

          if (isset($user_exists))
          {
            $user_permission = sfGuardUserPermissionPeer::retrieveByPK($user_id, $permission_id);
            if ($user_permission)
            {
              $user_permission->delete();
            }
            else
            {
              $user_permission = new sfGuardUserPermission();
              $user_permission->setUserId($user_id);
              $user_permission->setPermissionId($permission_id);
              $user_permission->save();
              $this->getResponse()->setStatusCode(201);
            }
          }
          elseif (isset($group_exists))
          {
            $group_permission = sfGuardGroupPermissionPeer::retrieveByPK($group_id, $permission_id);
            if ($group_permission)
            {
              $group_permission->delete();
            }
            else
            {
              $group_permission = new sfGuardGroupPermission();
              $group_permission->setGroupId($group_id);
              $group_permission->setPermissionId($permission_id);
              $group_permission->save();
              $this->getResponse()->setStatusCode(201);
            }
          }
        }
      }

      if (!$request->isXmlHttpRequest())
        $this->redirect('@sf_plop_dashboard_permissions');
    }

    $this->groups = sfPlopGuard::getAllGroups();
    $this->users = sfPlopGuard::getAllUsers();
    $this->permissions = sfPlopGuard::getAllPermissions();
  }

}
