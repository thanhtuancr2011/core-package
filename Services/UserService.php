<?php namespace Rowboat\Users\Services;

use Rowboat\Users\Models\PermissionModel;
use Rowboat\Users\Models\RoleModel;
use Rowboat\Users\Models\UserGroupModel;

class UserService{

    /**
     * getListPermission sync of user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  [type] $idsData [description]
     * @return [type]          [description]
     */
    public static function getListPermissionSyncOfUser($idsData)
    {
        $permissionNames = PermissionModel::whereIn('id', $idsData)->lists('name')->all();

        return implode(', ', $permissionNames);
    }

    /**
     * get list Roles syn of user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  [type] $idsData [description]
     * @return [type]          [description]
     */
    public static function getListRolesSyncOfUser($idsData)
    {
        $rolesName = RoleModel::whereIn('id', $idsData)->lists('name')->all();

        return implode(', ', $rolesName);
    }
}

