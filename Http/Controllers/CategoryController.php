<?php

namespace Comus\Core\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use Comus\Core\Models\CategoryModel;
use App\Http\Controllers\Controller;
use Comus\Core\Models\ImageModel;

class CategoryController extends Controller
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
     * Display a listing of categories.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if (Auth::user()->is('super.mod') || Auth::user()->is('super.admin')) {
            $categories = CategoryModel::all();
            $listsMapCategories = CategoryModel::lists('name', 'id');
            return view('core::category.index', compact('categories', 'listsMapCategories'));
        }
        return redirect('/');
    }

    /**
     * Show the form for creating a new category.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->is('super.mod') || Auth::user()->is('super.admin')) {
            $category = new CategoryModel;
            /* Call function get tree category */
            $categoriesTree = $category->getCategoriesTree();
            /* Choose first category is category selected */
            $categorySelected = $category->first();
            /* File upload */
            $filesUpload = new ImageModel;
            
            return view('core::category.create', compact('category', 'categoriesTree', 'categorySelected', 'filesUpload'));
        }
        return redirect('/');
    }

    /**
     * Edit category
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Int $id Category id
     * @return Void     
     */
    public function edit($id)
    {
        if (Auth::user()->is('super.mod') || Auth::user()->is('super.admin')) {
            
            // Init model
            $categoryModel = new CategoryModel;        

            // Call function get tree category
            $categoriesTree = $categoryModel->getCategoriesTree();

            // Category edit
            $category = CategoryModel::findOrFail($id);

            // Category parent
            $categorySelected = CategoryModel::findOrFail($category->parent_id);

            // If not parent id then cateogy parent is root category
            if (empty($categorySelected)) {
                $categorySelected = $categoryModel->first();
            }

            $categorySelected['ancestor_ids'] = explode(',', $categorySelected['ancestor_ids']);

            $filesUpload = $category->images;

            return view('core::category.create', compact('category', 'categoriesTree', 'categorySelected', 'filesUpload'));
        }

        return redirect('/');
    }
}
