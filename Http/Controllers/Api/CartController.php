<?php

namespace Comus\Core\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests;
use Comus\Core\Models\ProductModel;
use App\Http\Controllers\Controller;
use Cart;

class CartController extends Controller
{
    /**
     * Add product to cart
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param String $productId Id of product
     * @return Response 
     */
    public function addProductToCart ($productId)
    {
        // Get product
        $product = ProductModel::findOrFail($productId);
        // Get image of product
        $image = $product->images()->select('folder', 'stored_file_name')->first();
        //Image path of product
        $imagesPath = $image->folder . $image->stored_file_name;
        // Add product to cart
        Cart::instance('shopping')
            ->add(array(
                'id'      => $product->id, 
                'name'    => $product->name, 
                'qty'     => 1, 
                'price'   => $product->price, 
                'options' => array('imagesPath' => $imagesPath)
            )
        );
        // Get cart content
        $carts = getCarts();
        // Price total of cart
        $priceTotal = getPriceTotal();
        // Number of items
        $numberItem = getNumberItem();

        return new JsonResponse(['carts' => $carts, 'priceTotal' => $priceTotal, 'numberItem' => $numberItem]);
    }

    /**
     * Update shopping cart
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  String  $id      Row id of cart
     * @param  Request $request Request
     * @return Array            Json response
     */
    public function update($id, Request $request)
    {
        $data = $request->all();
        $cart = Cart::instance('shopping')->update($id, $data);
        // Get cart content
        $carts = getCarts();
        // Price total of cart
        $priceTotal = getPriceTotal();
        // Number of items
        $numberItem = getNumberItem();

        return new JsonResponse(['carts' => $carts, 'priceTotal' => $priceTotal, 'numberItem' => $numberItem]);
    }

    /**
     * Delete shopping cart
     * @author Thanh Tuan <thanhtuancr2011@gmail.com
     * @param String $id Cart id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        Cart::instance('shopping')->remove($id);
        // Get cart content
        $carts = getCarts();
        // Price total of cart
        $priceTotal = getPriceTotal();
        // Number of items
        $numberItem = getNumberItem();

        return new JsonResponse(['carts' => $carts, 'priceTotal' => $priceTotal, 'numberItem' => $numberItem]);
    }
}
