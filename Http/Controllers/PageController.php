<?php 

namespace Comus\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Comus\Core\Models\ArticleModel;
use Illuminate\Http\Request;
use Comus\Core\Http\Requests\ArticleFormRequest;
use Illuminate\Http\JsonResponse;

class PageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Article Controller
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
     * Show lists article
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Response
     */
    public function index()
    {
        if(\Auth::user()->can('user.admin')) {
            $articleModel = new ArticleModel;
            $type = 'page';
            $items = $articleModel->getAllItemWithType(array('page'));
            
            return view('core::article.index', compact('items', 'type'));
        }
    }

    /**
     * Show the form for creating a new article.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->is('super.admin')) {
            /* Init model */
            $item = new ArticleModel;
            $filesUpload = array();
            $type = 'page';
            return view('core::article.create', compact('item', 'type', 'filesUpload'));
        }

        return redirect('/admin/category');
    }

    /**
     * Show the form for edit a article.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::user()->is('super.admin')) {
            $item = ArticleModel::findOrFail($id);
            $filesUpload = $item->images;
            $type = 'page';
            return view('core::article.create', compact('item', 'type', 'filesUpload'));
        }

        return redirect('/admin/category');
    }
}
