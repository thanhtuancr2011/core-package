<?php 

namespace Comus\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Comus\Core\Models\CollectionModel;
use Illuminate\Http\Request;
use Comus\Core\Http\Requests\CollectionFormRequest;
use Illuminate\Http\JsonResponse;
use Comus\Core\Models\ImageModel;

class CollectionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Collection Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show lists collection
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Response
     */
    public function index()
    {
        if(\Auth::user()->can('user.admin')) {
            $items = CollectionModel::all();
            
            return view('core::collection.index', compact('items'));
        }
    }

    /**
     * Show the form for creating a new collection.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->is('super.admin')) {
            $item = new CollectionModel;
            $filesUpload = new ImageModel;
            return view('core::collection.create', compact('item', 'filesUpload'));
        }

        return redirect('/admin/category');
    }

    /**
     * Show the form for edit a collection.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::user()->is('super.admin')) {
            $item = CollectionModel::findOrFail($id);
            $filesUpload = $item->images;
            return view('core::collection.create', compact('item', 'filesUpload'));
        }

        return redirect('/admin/category');
    }
}
