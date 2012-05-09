<?php slot('sf_plop_theme', sfPlop::getAdminTheme($sf_user->getProfile()->getTheme(), true, true)) ?>

<?php slot('sf_plop_admin'); ?>
  <?php include_partial('sfPlopDashboard/toolbarDashboard', array(
    'tabs' => $tabs,
    'sub' => $sub,
  )) ?>
<?php end_slot(); ?>

<h1><?php echo __('Credentials', '', 'plopAdmin') ?></h1>

<<?php echo html5Tag('section'); ?> class="section lcr">

  <h2><?php echo __('Credentials of the groups', '', 'plopAdmin') ?></h2>
  <p><?php echo __('Here, you can set or remove permissions to groups.', '', 'plopAdmin') ?></p>

  <table class="w-table" id="group-permissions">

    <tr>
      <th rowspan="2" colspan="2"></th>
      <th colspan="<?php echo count($groups) ?>"> <?php echo __('Groups', '', 'plopAdmin') ?></th>
    </tr>
    <tr>
      <?php foreach ($groups as $group): ?>
        <th>
          <?php if (in_array('sfGuardGroup', sfPlop::getSafePluginModules())
            && $sf_user->hasCredential('sfGuardGroup')
          ): ?>
            <?php echo link_to(
              __($group->getDescription(), '', 'plopAdmin'),
              'sfGuardGroup/edit?id=' . $group->getId()
            ); ?>
          <?php else: ?>
            <?php echo __($group->getDescription(), '', 'plopAdmin'); ?>
          <?php endif; ?>
        </th>
      <?php endforeach; ?>
    </tr>

    <?php $i = 0; foreach ($permissions as $permission): ?>
      <tr>
        <?php if ($i == 0): ?>
          <th rowspan="<?php echo count($permissions) ?>"><?php echo __('Permissions', '', 'plopAdmin') ?></th>
        <?php endif; ?>
        <th>
          <?php echo __($permission['description'], '', 'plopAdmin') ?>
        </th>
        <?php foreach ($groups as $group): ?>
          <?php $group_has_permission = sfPlopGuard::groupHasPermission($group->getId(), $permission['id']); ?>
          <td>
            <?php echo link_to(
              image_tag('/sfPlopPlugin/vendor/famfamfam/silk/'
                . ($group_has_permission ? 'accept' : 'cross')
                . '.png'
              ),
              '@sf_plop_dashboard_permissions?g=' . $group->getId() . '&p=' . $permission['id'],
              array(
                'class' => 'w-ajax w-ajax-n w-admin-credential',
                'data-value' => ($group_has_permission ? 'true' : 'false'),
                'data-method' => 'POST'
              )
            ) ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php $i++; endforeach; ?>

  </table>

</<?php echo html5Tag('section'); ?>>


<<?php echo html5Tag('section'); ?> class="section lcr">

  <h2><?php echo __('Groups of the users', '', 'plopAdmin') ?></h2>
  <p><?php echo __('Here, you can associate users to groups. Note : you can also give the "super admin" credential to users. But use carefully the "super admin" credential because this grants all the permissions to a user even if you did not let him a specific one.', '', 'plopAdmin') ?></p>

  <table class="w-table" id="user-groups">

    <tr>
      <th rowspan="2" colspan="2"></th>
      <th colspan="<?php echo count($groups) + 1 ?>"> <?php echo __('Groups', '', 'plopAdmin') ?></th>
    </tr>
    <tr>
      <th><?php echo __('Super admin', '', 'plopAdmin') ?></th>
      <?php foreach($groups as $group): ?>
        <th>
          <?php echo __($group->getDescription(), '', 'plopAdmin') ?>
        </th>
      <?php endforeach; ?>
    </tr>

    <?php $i = 0; foreach($users as $user): ?>
      <tr>
        <?php if ($i == 0): ?>
          <th rowspan="<?php echo count($users) ?>"><?php echo __('Users', '', 'plopAdmin') ?></th>
        <?php endif; ?>
        <th>
          <?php if (in_array('sfGuardUser', sfPlop::getSafePluginModules())
            && $sf_user->hasCredential('sfGuardUser')
          ): ?>
            <?php echo link_to(
              $user->getUsername(),
              'sfGuardUser/edit?id=' . $user->getId()
            ); ?>
          <?php else: ?>
            <?php echo $user->getUsername(); ?>
          <?php endif; ?>
        </th>
        <td>
          <?php echo link_to(
            image_tag('/sfPlopPlugin/vendor/famfamfam/silk/'
              . ($user->getIsSuperAdmin() ? 'accept' : 'cross')
              . '.png'
            ),
            '@sf_plop_dashboard_permissions?u=' . $user->getId() . '&p=super',
            array(
              'class' => 'w-ajax w-ajax-n w-admin-credential',
              'data-value' => ($user->hasPermission($permission['name']) ? 'true' : 'false'),
              'data-method' => 'POST'
            )
          ) ?>
        </td>
        <?php foreach($groups as $group): ?>
          <?php $user_has_group = $user->hasGroup($group->getName()); ?>
          <td>
            <?php echo link_to(
              image_tag('/sfPlopPlugin/vendor/famfamfam/silk/'
                . ($user_has_group ? 'accept' : 'cross')
                . '.png'
              ),
              '@sf_plop_dashboard_permissions?u=' . $user->getId() . '&g=' . $group->getId(),
              array(
                'class' => 'w-ajax w-ajax-n w-admin-credential',
                'data-value' => ($user_has_group ? 'true' : 'false'),
                'data-method' => 'POST'
              )
            ) ?>
          </td>
        <?php $i++; endforeach; ?>
      </tr>
    <?php endforeach; ?>

  </table>

</<?php echo html5Tag('section'); ?>>
