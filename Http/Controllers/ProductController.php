<?php

namespace Comus\Core\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use Comus\Core\Models\ProductModel;
use Comus\Core\Models\CategoryModel;
use App\Http\Controllers\Controller;
use Comus\Core\Models\ImageModel;


class ProductController extends Controller
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
     * View all products
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Void 
     */
    public function index()
    {
        if (Auth::user()->is('super.mod') || Auth::user()->is('super.admin')) {
            $products = ProductModel::all();
            $listsMapCategories = CategoryModel::lists('name', 'id');
            return view('core::product.index', compact('products', 'listsMapCategories'));
        }

        return redirect('/');
    }

    /**
     * Create new product
     * @author Thanh Tuan <thanhtuancr2011@gmail.com> 
     * @return Void 
     */
    public function create()
    {
        if (Auth::user()->is('super.mod') || Auth::user()->is('super.admin')) {
            $product = new ProductModel;
            $categoryModel = new CategoryModel;

            // Call function get tree category
            $categoriesTree = $categoryModel->getCategoriesTree();
            $categoriesTree = $categoriesTree[0]['subFolder'];

            $categorySelected = $categoryModel;
            $categorySelected['name'] = 'Category';

            $filesUpload = new ImageModel;
            
            return view('core::product.create', compact('product', 'categoriesTree', 'categorySelected', 'filesUpload'));
        }
        return redirect('/');
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Edit product
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Int $id Product id
     * @return Void     
     */
    public function edit($id)
    {
        if (Auth::user()->is('super.mod') || Auth::user()->is('super.admin')) {

            // Category edit
            $product = ProductModel::findOrFail($id);
            
            $product->color = explode(',', $product->color);

            // Init model
            $categoryModel = new CategoryModel;

            // Call function get tree category
            $categoriesTree = $categoryModel->getCategoriesTree();
            $categoriesTree = $categoriesTree[0]['subFolder'];

            // Category selected
            $categorySelected = CategoryModel::findOrFail($product->category_id);

            $filesUpload = $product->images;

            return view('core::product.create', compact('product', 'categoriesTree', 'categorySelected', 'filesUpload'));
        }

        return redirect('/');
    }
}
