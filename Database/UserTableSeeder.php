<?php
namespace Comus\Core\Database;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Comus\Core\Models\UserModel;
use Bican\Roles\Models\Role as RoleModel;
use Bican\Roles\Models\Permission as PermissionModel;

use App\Models\WorkInformationModel;
use App\Models\PersonalInformationModel;

class UserTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->where('email', 'admin@comus.com')->delete();
        \DB::table('roles')->where('slug', 'super.admin')->delete();
        \DB::table('permissions')->where('slug', 'user.admin')->delete();
        
        // Create role for admin
        $adminRole = RoleModel::create([
            'name' => 'Super Admin',
            'display_name' => 'Super Admin',
            'slug' => 'super.admin',
            'description' => 'Permission of admin user', // optional
            'level' => 1, // optional, set to 1 by default
        ]);

        // Create permission for admin and moderator
        $adminPermission = PermissionModel::create([
            'name' => 'User Admin',
            'display_name' => 'User Admin',
            'slug' => 'user.admin',
            'description' => 'User User Administrator', // optional
        ]);
        
        // Create user and assign role is admin
        $userAdmin = UserModel::create([
            'first_name' => 'admin',
            'last_name'=>'admin',
            'email'=>'admin@comus.com',
            'remember_token' => str_random(40),
            'password'=>bcrypt('admin')
        ]);

        $userAdmin->attachRole($adminRole);

        $userAdmin->attachPermission($adminPermission);
    }

}
