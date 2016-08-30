<?php 

namespace Comus\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Comus\Core\Models\RoleModel;
use Illuminate\Http\Request;
use Comus\Core\Http\Requests\RoleFormRequest;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
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
     * Show lists role
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Response
     */
    public function index()
    {
        if(\Auth::user()->can('user.admin')) {

            $roleModel = new RoleModel;

            $items = $roleModel->getAllRoles();
            
            return view('core::role.index', compact('items'));
        }
    }

    /**
     * Show the form for creating a new role.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->is('super.admin')) {

            /* Init user */
            $item = new RoleModel;

            return view('core::role.create', compact('item'));
        }

        return redirect('/admin/category');
    }

    /**
     * Show the form for edit a role.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::user()->is('super.admin')) {
            /* Find role */
            $item = RoleModel::findOrFail($id);

            return view('core::role.create', compact('item'));
        }

        return redirect('/admin/category');
    }
}
