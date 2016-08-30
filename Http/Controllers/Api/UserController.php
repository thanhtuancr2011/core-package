<?php

namespace Comus\Core\Http\Controllers\Api;

use Illuminate\Http\Request;

use Auth;
use Input;
use App\Http\Requests;
use Comus\Core\Models\UserModel;
use Comus\Core\Models\RoleModel;
use Comus\Core\Models\PermissionModel;
use Comus\Core\Services\FileService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Comus\Core\Http\Requests\UserFormRequest;

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
     * Create a user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request)
    {
        $status = 0;

        /* Get all data input */
        $data = $request->all();

        /* Init user model to call function in it */
        $userModel = new UserModel;
        
        /* Call function create new user */
        $user = $userModel->createNewUser($data);

        /* If user was created */
        if ($user) {
            $status = 1;
        }

        /* Return user */
        return new JsonResponse(['user' => $user, 'status' => $status]);
    }

    /**
     * Update a user.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, $id)
    {
        $status = 0;

        /* Get all data input */
        $data = $request->all();

        /* Find user */
        $user = UserModel::findOrFail($id);

        /* Call function create new user */
        $result = $user->updateUser($data);

        /* If user was created */
        if ($result) {
            $status = 1;
        }
        
        /* Return user */
        return new JsonResponse(['user' => $result, 'status' => $status]);
    }

    /**
     * Remove a user.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* Find user */
        $user = UserModel::findOrFail($id);

        /* Delete user */
        $status = $user->delete();

        return new JsonResponse(['status'=>$status]);

    }

    /**
     * Update profile of user 
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>   
     * @param  Request $request Request
     * @param  Int     $userId  Id of user 
     * @return \Illuminate\Http\Response
     */
    public function updateProfile($userId, UserFormRequest $request)
    {
        /* If user has role or permission or update self */ 
        if(\Auth::user()->is('super.admin') || \Auth::user()->can('user.admin') || \Auth::user()->id == $userId ){
            
            /* Get all data */
            $data = $request->all();
            
            /* Find user */
            $user = UserModel::findOrFail($userId);
            
            /* Call function update user */
            $result = $user->updateUser($data);
            
            return new JsonResponse($result);
        }
    }

    /**
     * Change avatar for user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Request $request Request
     * @param  String  $id      Id of user 
     * @return Array            
     */
    public function changeAvatar(Request $request, $id = null)
    {
        // If user has role or permission or update self
        if(\Auth::user()->is('super.admin') || \Auth::user()->can('user.admin') || \Auth::user()->id == $id ){

            /* Get all data input */ 
            $data = $request->all();
            
            /* Find user */ 
            $user = UserModel::find($id);

            /* If has file image and has user */ 
            if(!empty($data['file']) && !empty($user)){

                $result = $user->changeAvatarUser($data, $id);

                /* If upload file isn't error */
                if(!isset($result['error'])){
                    return new JsonResponse(['status' => 1, 'item' => $result]);
                } else{
                    return  new JsonResponse(['status' => 0, 'error' => $result['error']]);
                }
            }
            return new JsonResponse(['status' => 0]);
        }
    }

    /**
     * Change password for user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Request $request Request
     * @param  String  $id      Id of user 
     * @return Response         
     */
    public function changePassword(Request $request)
    {
        // If user has role or permission or update self
        if(\Auth::user()->is('super.admin') || \Auth::user()->can('user.admin') || \Auth::user()->id == $id ){

            $status = 0;
            
            /* Get all data input */ 
            $data = $request->all();

            // Find user
            $user = UserModel::find($data['userId']);

            $status = $user->changePasswordUser($data);

            return new JsonResponse(['status' => $status]);
        }
    }

    /**
     * Check email user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Request $request Request
     * @return Response           
     */
    public function checkEmailProfile(Request $request)
    {
        $data = $request->all();
        
        /* Find user */
        $user = UserModel::find($data['id']);

        /* Call function check unique email */
        $status = $user->checkUniqueEmailUser($data);

        return new JsonResponse(['status' => $status]);
    }

    /**
     * Update permission for role
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function updatePermission($id, Request $request)
    {
        if(\Auth::user()->can('user.admin')){

            /* Check permission deleted befor update */
            $message = 'One of permissions is deleted';
            $permissionIds = $request->get('permissionIds', []);
            $countPer = PermissionModel::whereIn('id',$permissionIds)->count();
            if($countPer != count($permissionIds)) {
                return new JsonResponse(['status' => 0, 'message' => $message]);
            }

            /* Check has user */
            $user = UserModel::find($id);
            if(empty($user)){
                abort('User not found');
            }

            /* Delete permission of user */
            $status = $user->userPermissions()->sync($permissionIds);

            return ['status'=>$status];
        }
    }

    /**
     * Update role for role
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function updateRole($id, Request $request)
    {
        if(\Auth::user()->can('user.admin')){
            /* Check role deleted befor update */
            $roleIds = $request->get('roleIds', []);
            $countRole = RoleModel::whereIn('id', $roleIds)->count();
            if($countRole != count($roleIds)) {
                return new JsonResponse(['status' => 0, 'message' => 'One of roles is deleted']);
            }

            /* Check has user */
            $user = UserModel::find($id);
            if(empty($user)){
                abort('User not found');
            }

            /* Update role of user */
            $status = $user->roles()->sync($roleIds);
        }
        return;
    }
}
