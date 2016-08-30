<?php 

namespace Comus\Core\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Bican\Roles\Models\Role;
use Comus\Core\Models\RoleModel;
use Comus\Core\Models\PermissionModel;
use App\Http\Controllers\Controller;
use Comus\Core\Http\Requests\RoleFormRequest;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Role Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

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
     * Create a role
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleFormRequest $request)
    {
        $status = 0;

        /* Get all data input */
        $data = $request->all();

        /* Init role model to call function in it */
        $roleModel = new RoleModel;
        
        /* Call function create new role */
        $role = $roleModel->createNewRole($data);

        /* If role was created */
        if ($role) {
            $status = 1;
        }

        /* Return user */
        return new JsonResponse(['role' => $role, 'status' => $status]);
    }

    /**
     * Update a role.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleFormRequest $request, $id)
    {
        $status = 0;

        /* Get all data input */
        $data = $request->all();

        /* Find role */
        $role = RoleModel::findOrFail($id);

        /* Call function create new role */
        $result = $role->updateRole($data);

        /* If role was created */
        if ($result) {
            $status = 1;
        }
        
        /* Return role */
        return new JsonResponse(['role' => $result, 'status' => $status]);
    }

    /**
     * Remove a role.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* Find role */
        $role = RoleModel::findOrFail($id);

        /* Delete role */
        $status = $role->delete();

        return new JsonResponse(['status' => $status]);
    }
}
