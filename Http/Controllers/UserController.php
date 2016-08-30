<?php

namespace Comus\Core\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Comus\Core\Models\UserModel;
use Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the users.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->is('super.admin')) {

            /* Init user model to call function in it */
            $userModel = new UserModel;

            /* Call function get all user */
            $users = $userModel->getAllUser();

            return view('core::users.index', compact('users'));
        }
        return redirect('/admin/category');
    }

    /**
     * Show the form for creating a new user.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->is('super.admin')) {

            /* Init user */
            $item = new UserModel;

            return view('core::users.create', compact('item'));
        }
        return redirect('/admin/category');
    }

    /**
     * Display the profile of user.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Int  $id
     * @return \Illuminate\Http\Response
     */
    public function profileUser($id)
    {
        if (Auth::user()->is('super.admin') || \Auth::user()->can('user.admin') || (Auth::user()->id == $id)) {
            /* Find user */
            $item = UserModel::findOrFail($id);

            /* Set avatar default for user if user is empty avatar */
            if (empty($item->avatar)) {
                $item->avatar = '160x160_avatar_default.png?t=1';
            }

            return view('core::users.profile', compact('item'));
        }
        return redirect('/admin/category');
    }

    /**
     * Show the form for edit a user.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->is('super.admin')) {
            /* Find user */
            $item = UserModel::findOrFail($id);

            return view('core::users.create', compact('item'));
        }
        return redirect('/admin/category');
    }

    /**
     * Call modal change password of user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function changePassword()
    {
        return view('core::users.change-password');
    }

    /**
     * show permissions fo role that user can update it
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function permissions($id)
    {
        if(\Auth::user()->can('user.admin')){
            
            $user = UserModel::find($id);

            if(empty($user)){
                abort('User not found');
            }

            /* Get permissions of user */
            $userPermissions = [];
            $userPermissionsData = $user->userPermissions()->get();
            foreach ($userPermissionsData as $key => $value) {
                $userPermissions[$value->id] = $value;
            }

            /* Get permission not belong to user */
            $listPermissionsData = \DB::table('permissions')->whereNotIn('id', array_keys($userPermissions))->get();
            $listPermissions = [];
            foreach ($listPermissionsData as $key => $value) {
                $listPermissions[$value->id] = $value;
            }

            /* Get roles of user */
            $userRolesData = $user->roles()->get();
            $userRoles = [];
            foreach($userRolesData as $key => $value) {
                $userRoles[$value->id] = $value;
            }

            /* Get roles not belong to user */
            $listRolesData = \DB::table('roles')->whereNotIn('id',array_keys($userRoles))->get();
            $listRoles = [];
            foreach($listRolesData as $key => $value) {
                $listRoles[$value->id] = $value;
            }

            return view('core::users.permission', compact('user', 'listPermissions', 'userPermissions', 'userRoles', 'listRoles', 'id'));
        }
         return redirect('/');
    }
}
