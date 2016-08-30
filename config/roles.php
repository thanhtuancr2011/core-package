<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Package Connection
    |--------------------------------------------------------------------------
    |
    | You can set a different database connection for this package. It will set
    | new connection for models Role and Permission. When this option is null,
    | it will connect to the main database, which is set up in database.php
    |
    */

    'connection' => null,

    /*
    |--------------------------------------------------------------------------
    | Slug Separator
    |--------------------------------------------------------------------------
    |
    | Here you can change the slug separator. This is very important in matter
    | of magic method __call() and also a `Slugable` trait. The default value
    | is a dot.
    |
    */

    'separator' => '.',

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | If you want, you can replace default models from this package by models
    | you created. Have a look at `Bican\Roles\Models\Role` model and
    | `Bican\Roles\Models\Permission` model.
    |
    */

    'models' => [
        'role' => Comus\Core\Models\RoleModel::class,
        'permission' => Comus\Core\Models\PermissionModel::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Roles, Permissions and Allowed "Pretend"
    |--------------------------------------------------------------------------
    |
    | You can pretend or simulate package behavior no matter what is in your
    | database. It is really useful when you are testing you application.
    | Set up what will methods is(), can() and allowed() return.
    |
    */

    'pretend' => [

        'enabled' => false,

        'options' => [
            'is' => true,
            'can' => true,
            'allowed' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Bican role_user Table
    |--------------------------------------------------------------------------
    |
    | This is the role_user table used by Bican to save assigned roles to the
    | database.
    |
    */
    'role_user_table' => 'role_user',

    /*
    |--------------------------------------------------------------------------
    | Bican permission_user Table
    |--------------------------------------------------------------------------
    |
    | This is the permission_user table used by Bican to save assigned permission to the
    | database.
    |
    */
    'permission_user_table' => 'permission_user',

    /*
    |--------------------------------------------------------------------------
    | Bican permission_role Table
    |--------------------------------------------------------------------------
    |
    | This is the permission_role table used by Bican to save assigned permission to the
    | database.
    |
    */
    'permission_role_table' => 'permission_role',

    /*
    |--------------------------------------------------------------------------
    | Bican type_permisison Table
    |--------------------------------------------------------------------------
    |
    | This is the type_permission table used by Bican to save assigned permission to the
    | database.
    |
    */
    'type_permission_table' => 'type_permission',
];
