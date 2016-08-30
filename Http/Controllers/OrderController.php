<?php

namespace Comus\Core\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use Comus\Core\Models\OrderModel;
use App\Http\Controllers\Controller;


class OrderController extends Controller
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
     *
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * 
     * @return Void 
     */
    public function index()
    {
        if (Auth::user()->is('super.mod') || Auth::user()->is('super.admin')) {
            // Init model
            $orderModel = new OrderModel;
            // Get order and customer of order
            $listOrders = $orderModel->getOrder();

            return view('core::order.index', compact('listOrders'));
        }

        return redirect('/');
    }

    /**
     * Create new product
     *
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * 
     * @return Void 
     */
    public function create()
    {
        if (Auth::user()->is('super.mod') || Auth::user()->is('super.admin')) {
        }
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Edit product
     *
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * 
     * @param  Int $id Product id
     * 
     * @return Void     
     */
    public function edit($id)
    {
        if (Auth::user()->is('super.mod') || Auth::user()->is('super.admin')) {
        }

        return redirect('/');
    }
}
