<?php 

/**
 * Get cart content
 * @author Thanh Tuan <thanhtuancr2011@gmail.com>
 * @return Object Cart
 */
function getCarts()
{
    $carts = \Cart::instance('shopping')->content();
    
    return $carts;
}

/**
 * Get the price total of cart
 * @author Thanh Tuan <thanhtuancr2011@gmail.com>
 * @return float
 */
function getPriceTotal()
{
	$total = \Cart::instance('shopping')->total();

    return $total;
}

/**
 * Get the number of items in the cart
 * @author Thanh Tuan <thanhtuancr2011@gmail.com>
 * @param  boolean $totalItems Get all the items (when false, will return the number of rows)
 * @return int
 */

function getNumberItem()
{
	$numberItems = \Cart::instance('shopping')->count(false);

    return $numberItems;
}

/**
 * Destroy cart
 * @author Thanh Tuan <thanhtuancr2011@gmail.com>
 * @return Void 
 */
function destroyCart()
{
	\Cart::instance('shopping')->destroy();
}