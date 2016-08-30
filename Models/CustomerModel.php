<?php

namespace Comus\Core\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomerModel extends Authenticatable
{

    protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fullname', 'email', 'remember_token', 'password', 'description', 'avatar', 'phone', 'provider', 'birth_date'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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

        return $user;
    }
}
