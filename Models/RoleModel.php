<?php  

namespace Comus\Core\Models;

use Illuminate\Support\Facades\Config;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;

class RoleModel extends Role
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'slug', 'description', 'level'];

    /**
     * Many-to-Many relations with the user model.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Config::get('auth.model'), Config::get('roles.role_user_table'), 'role_id', 'user_id');
    }

    /**
     * Many-to-Many relations with the permission model.
     * Named "perms" for backwards compatibility. Also because "perms" is short and sweet.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perms()
    {
        return $this->belongsToMany(Config::get('roles.models.permission'), Config::get('roles.permission_role_table'), 'role_id', 'permission_id');
    }  

    /**
     * Get all Roles
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Array Users
     */
    public function getAllRoles()
    {
        /* Get all user */
        $roles = self::all();

        return $roles;
    }

    /**
     * Create New Role description
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function createNewRole($data)
    {
        /* Create new role */
        $role = self::create($data);

        return $role;
    }

    /**
     * Update role
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $data  Data input 
     * @return Object       Role
     */
    public function updateRole($data)
    {
        /* Update role */
        $this->update($data);
        
        return $this;
    }
}
