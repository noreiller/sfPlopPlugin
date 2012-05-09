<?php

class sfPlopGuard
{
  /**
   * Retrieve all the users.
   * @return Array with all the sfGuardUser objects
   */
  public static function getAllUsers()
  {
    return sfGuardUserQuery::create()->find();
  }

  /**
   * Check if a user exists given to an id.
   * @param Int $user_id
   * @return Boolean
   */
  public static function userExists($id)
  {
    return sfGuardUserPeer::retrieveByPK($id) ? true : false;
  }

  /**
   * Check if a user is the last super admin
   * @return Boolean
   */
  public static function isLastSuperAdminUser()
  {
    return sfGuardUserQuery::create()
      ->filterByIsSuperAdmin(true)
      ->count() > 1 ? false : true;
  }

  /**
   * Retrieve all the groups.
   * @return Array with all the sfGuardGroup objects
   */
  public static function getAllGroups()
  {
    return sfGuardGroupQuery::create()->find();
  }

  /**
   * Check if a group exists given to an id.
   * @param Int $group_id
   * @return Boolean
   */
  public static function groupExists($id)
  {
    return sfGuardGroupPeer::retrieveByPK($id) ? true : false;
  }

  /**
   * Check if a group has a permission given to thier ids
   * @param Int $group_id
   * @return Boolean
   */
  public static function groupHasPermission($group_id, $permission_id)
  {
    return sfGuardGroupPermissionPeer::retrieveByPK($group_id, $permission_id) ? true : false;
  }

  /**
   * Retrieve all the permissions.
   * @return Array with all the sfGuardPermission objects
   */
  public static function getAllPermissions()
  {
    $array = array();
    $permissions = sfGuardPermissionPeer::doSelect(new Criteria());

    foreach($permissions as $permission)
      if (in_array($permission->getName(), array_keys(sfPlop::getSafePluginModules())))
        $array [$permission->getName()] = array(
          'id' => $permission->getId(),
          'name' => $permission->getName(),
          'description' => $permission->getDescription()
        );

    foreach(sfPlop::getSafePluginModules() as $key => $options)
      if (!isset($array[$key]))
        $array [$key] = array(
          'id' => $key,
          'name' => $key,
          'description' => $options['name']
        );

    return $array;
  }

  /**
   * Check if a permission exists given to an id or a name.
   * @param Mixed $permission
   * @return Boolean
   */
  public static function permissionExists($permission)
  {
    $status = false;
    if (sfGuardPermissionPeer::retrieveByPK($permission))
      $status = true;
    if (!$status && sfGuardPermissionPeer::retrieveByName($permission))
      $status = true;

    return $status;
  }

  /**
   * Get a permission given to an id or a name.
   * @param Mixed $permission
   * @return Boolean
   */
  public static function getPermission($permission)
  {
    $return = sfGuardPermissionPeer::retrieveByPK($permission);
    if (!$return)
      $return = sfGuardPermissionPeer::retrieveByName($permission);

    return $return;
  }
}

?>
