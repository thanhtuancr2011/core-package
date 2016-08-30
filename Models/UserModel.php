<?php

namespace Comus\Core\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bican\Roles\Models\Role as RoleModel;
use Bican\Roles\Models\Permission as PermissionModel;
use Comus\Core\Services\FileService;

class UserModel extends BaseUserModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, SoftDeletes;

    protected $dates = ['deleted_at'];
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'remember_token', 'password', 'description', 'phone'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * Many-to-Many relations with Role.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Config::get('roles.models.role'), Config::get('roles.role_user_table'), 'user_id', 'role_id'); 
    }

    /**
     * User belongs to many permissions.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userPermissions()
    {
        return $this->belongsToMany(Config::get('roles.models.permission'), Config::get('roles.permission_user_table'), 'user_id', 'permission_id');
    }   

    /**
     * Get the articles for the user.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\hasMany 
     */
    public function articles()
    {
        return $this->hasMany('Comus\Core\Models\ArticleModel', 'user_id');
    }

    /**
     * Get all user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Array Users
     */
    public function getAllUser()
    {
        /* Get all user */
        $users = self::all();
        /* Each user */
        foreach ($users as &$user) {
            $user = $this->getProfileDataUser($user);
        }
        return $users;
    }

    /**
     * Get data profile for user
     * @author Tuan <thanhtuancr2011@gmail.com>
     * @param  Object $user User
     * @return Object       User
     */
    public function getProfileDataUser($user)
    {
        /* Format created date */
        $user->created_at = date('Y-m-d', strtotime($user->created_at));

        /* Get avatar for user */
        $user->avatar = $this->getAvatarUrl($user->avatar, '50x50');

        /* Get full name for user */
        $user->name = trim($user->last_name) . ' ' . trim($user->first_name);

        return $user;
    }

    /**
     * Get avatar url for user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  string $avatar Avatar
     * @param  String $size   Size of image 
     * @return String         Url
     */
    public function getAvatarUrl($avatar = '', $size = '50x50')
    {
        /* If user is not avatar then set default avatar for user */
        if(empty($avatar)){
            /* Init avatar */
            $avatar = '50x50_avatar_default.png?t=1';
        }

        return getBaseUrl() . '/core/avatars/'.$avatar;
    }

    /**
     * CreateNewUser description
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function createNewUser($data)
    {
        /* Encrypt password */
        $data['password'] = bcrypt($data['password']);

        /* Get remember token */
        $data['remember_token'] = str_random(40);

        /* Create new user */
        $user = self::create($data);

        /* Get mod role */
        $modRole = RoleModel::where('slug', 'super.mod')->first();

        /* Get mod permission */
        $modPermission = PermissionModel::where('slug', 'user.mod')->first();

        /* Attach mod role and mod permission for user is created */
        $user->attachRole($modRole);
        $user->attachPermission($modPermission);

        /* Get avatar for user */
        $user->avatar = $this->getAvatarUrl($user->avatar, '160x160');

        /* Get full name for user */
        $user->name = trim($user->last_name) . ' ' . trim($user->first_name);

        /* Format created date */
        $user->created_at = date('Y-m-d', strtotime($user->created_at));

        return $user;
    }

    /**
     * Create customer
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $data  Data input
     * @return Object       User created
     */
    public function createNewCustomer($data)
    {
        /* Encrypt password */
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        /* Create new user */
        $user = self::create($data);

        /* Format date created */
        $user->created_at = date('Y-m-d', strtotime($user->created_at));

        /* Get avatar for user */
        $user->avatar = $this->getAvatarUrl($user->avatar, '160x160');

        /* Get full name for user */
        $user->name = trim($user->last_name) . ' ' . trim($user->first_name);

        return $user;
    }

    /**
     * Update user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $data  Data input 
     * @return Object       User
     */
    public function updateUser($data)
    {
        /* Encrypt password if isset password */
        if (!empty($data['password']) || isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        /* Update user */
        $this->update($data);

        /* Format created date */
        $this->created_at = date('Y-m-d', strtotime($this->created_at));

        /* Get full name for user */
        $this->name = trim($this->last_name) . ' ' . trim($this->first_name);

        /* Get avatar for user */
        $this->avatar = $this->getAvatarUrl($this->avatar, '160x160');
        
        return $this;
    }

    /**
     * Check user is exists in system when edit 
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array $data Data input
     * @return Int         Status
     */
    public function checkUniqueEmailUser($data) 
    {
        $status = 1;

        /* Check unique email */
        if($this->email != $data['email']) {

            /* Count user has email same email input */
            $count = self::where('email', $data['email'])->where('email', '!=', $this->email)->count();

            if($count > 0) {
                $status = 0;
            }
        }

        return $status;
    }

    /**
     * Check email is exists in system when login with fb
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String $email Email
     * @return Void        
     */
    public function checkExistsEmail ($email)
    {
        $existsEmail = 0;

        /* Find user has email same $email */
        $user = self::where('email', $email)->first();

        if (!empty($user)) {
            $existsEmail = 1;
        }

        return $existsEmail;
    }

    /**
     * Change avatar for user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array  $data  Data input
     * @param  Int    $id    Id of user 
     * @return Void            
     */
    public function changeAvatarUser ($data, $id) 
    {
        /* Endcode and save image  */
        $data['file'] = str_replace('data:image/png;base64,', '', $data['file']);
        $data['file'] = str_replace(' ', '+', $data['file']);
        $result = FileService::saveAvatar(base64_decode($data['file']), $id);

        if(!is_array($result)){
            /* Save user */
            $this->avatar = $result;
            $this->save();
            return $this;
        }else{
            return $result['error'];
        }
    }

    /**
     * Change password for user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Array  $data  Data input
     * @return Int Status            
     */
    public function changePasswordUser ($data) 
    {
        $status = 0;
        /* Update user */
        $this->password = bcrypt($data['password']);
        $status = $this->save();

        return $status;
    }
}
