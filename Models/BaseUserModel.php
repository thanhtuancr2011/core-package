<?php 
namespace Comus\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Traits\HasRoleAndPermission;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;

class BaseUserModel extends Model implements HasRoleAndPermissionContract{
	use HasRoleAndPermission;
}