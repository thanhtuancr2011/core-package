<?php 

namespace Comus\Core\Services;

class RoleService
{
  	public static function getPermissionsAssigned($permissionsAvailableIds)
  	{
    	$permissions = \DB::table('permissions')
                    ->whereNotIn('id', $permissionsAvailableIds)
                    ->orderBy('name','asc')
                    ->get();
    	return $permissions;
  	}
}
