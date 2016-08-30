<?php 
namespace Comus\Core\Models;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class OrderDetailModel extends Model
{
    protected $table = 'order_details';

    protected $fillable = ['quantity', 'amount', 'status', 'order_id', 'product_id'];

    /**
	 * Get lists orders detail
	 *
	 * @author Thanh Tuan <thanhtuancr2011@Gmail.com>
	 * 
	 * @return Array Orders
	 */
	public function getListsOrdersDetail()
	{
		$ordersDetail = \DB::table('customers')
            ->join('orders', 'customers.id', '=', 'orders.user_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select('customers.fullname', 'orders.id', 'order_details.quantity', 'order_details.amount', 'order_details.status', 'products.name')
            ->get();

        return $ordersDetail;
	}
}