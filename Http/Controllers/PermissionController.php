<?php 

namespace Comus\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Comus\Core\Models\PermissionModel;
use Comus\Core\Http\Requests\PermissionFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Home Controller
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
     * Show lists permission
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Response
     */
    public function index()
    {
        if(\Auth::user()->can('user.admin')) {

            $permissionModel = new PermissionModel;

            $items = $permissionModel->getAllPermissions();
            
            return view('core::permission.index', compact('items'));
        }
    }

    /**
     * Show the form for creating a new permission.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->is('super.admin')) {

            /* Init user */
            $item = new PermissionModel;

            return view('core::permission.create', compact('item'));
        }

        return redirect('/admin/category');
    }

    /**
     * Show the form for edit a permission.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::user()->is('super.admin')) {
            /* Find permission */
            $item = PermissionModel::findOrFail($id);

            return view('core::permission.create', compact('item'));
        }

        return redirect('/admin/category');
    }
}
