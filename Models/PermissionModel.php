<?php 
namespace Comus\Core\Models;

use Illuminate\Support\Facades\Config;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;

class PermissionModel extends Permission
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'slug', 'description', 'level'];

    /**
     * Many-to-Many relations with role model.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Config::get('roles.models.role'), Config::get('roles.permission_role_table'), 'permission_id', 'role_id');
    }

    /**
     * Many-to-Many relations with the user model.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Config::get('auth.model'), Config::get('roles.permission_user_table'), 'permission_id', 'user_id');
    }

    /**
     * Get all Permissions
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Array Users
     */
    public function getAllPermissions()
    {
        /* Get all user */
        $permissions = self::all();

        return $permissions;
    }

    /**
     * Create New Permission description
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function createNewPermission($data)
    {
        /* Create new permission */
        $permission = self::create($data);

        return $permission;
    }

    /**
     * Update permission
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $data  Data input 
     * @return Object       Permission
     */
    public function updatePermission($data)
    {
        /* Update permission */
        $this->update($data);
        
        return $this;
    }
}
