<?php 
namespace Comus\Core\Models;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = 'orders';

    protected $fillable = ['amount', 'status_send_email', 'status_confirm', 'customer_id'];

    /**
     * Relationship with customer.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Voids 
     */
    public function customer()
    {
        return $this->belongsTo('Comus\Core\Models\CustomerModel', 'customer_id');
    }

    /**
     * Get orders and customer's orders.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Voids 
     */
    public function getOrder()
    {
    	// Get all orders
    	$orders = $this->all();

    	// Each order
    	foreach ($orders as $key => &$value) {
    		$value = $value->customer;
    	}

    	return $orders;
    }
}