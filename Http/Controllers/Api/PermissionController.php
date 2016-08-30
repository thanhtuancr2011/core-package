<?php 

namespace Comus\Core\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Comus\Core\Models\PermissionModel;
use Comus\Core\Http\Requests\PermissionFormRequest;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Permisson Controller
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
     * Create a permission
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionFormRequest $request)
    {
        $status = 0;

        /* Get all data input */
        $data = $request->all();

        /* Init permission model to call function in it */
        $permissionModel = new PermissionModel;
        
        /* Call function create new permission */
        $permission = $permissionModel->createNewPermission($data);

        /* If permission was created */
        if ($permission) {
            $status = 1;
        }

        /* Return user */
        return new JsonResponse(['permission' => $permission, 'status' => $status]);
    }

    /**
     * Update a permission.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionFormRequest $request, $id)
    {
        $status = 0;

        /* Get all data input */
        $data = $request->all();

        /* Find permission */
        $permission = PermissionModel::findOrFail($id);

        /* Call function create new permission */
        $result = $permission->updatePermission($data);

        /* If permission was created */
        if ($result) {
            $status = 1;
        }
        
        /* Return permission */
        return new JsonResponse(['permission' => $result, 'status' => $status]);
    }

    /**
     * Remove a permission.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* Find permission */
        $permission = PermissionModel::findOrFail($id);

        /* Delete permission */
        $status = $permission->delete();

        return new JsonResponse(['status' => $status]);

    }
}
