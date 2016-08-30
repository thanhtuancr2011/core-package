<?php

namespace Comus\Core\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Comus\Core\Models\OrderModel;
use Comus\Core\Services\MailService;
use Comus\Core\Services\CacheService;
use Comus\Core\Models\CustomerModel;
use Comus\Core\Http\Requests\CustomerFormRequest;
use Auth;

class CustomerController extends Controller
{
    /**
     * Create new customer
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  CustomerFormRequest $request Form request
     * @return Response                       
     */
    public function store(CustomerFormRequest $request) 
    {
        $status = 0;
        $data = $request->all();
        $data['remember_token'] = csrf_token();

        $customerModel = new CustomerModel;
        $customer = $customerModel->createNewCustomer($data);

        if ($customer) {
            $status = 1;
            $customer->password = $data['password'];
            // Send email create new account and login to customer
            MailService::sendEmailCreateAccount($customer);
            Auth::guard('customer')->login($customer);
        }

        return new JsonResponse(['status' => $status, 'customer' => $customer]);
    }

    /**
     * Login customer
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Request $request Request
     * @return Response           
     */
    public function postLogin (Request $request)
    {
        $data = $request->all();
        $customer = CustomerModel::where('email', $data['email'])->first();

        if (empty($customer)) {
            return new JsonResponse (['status' => 0, 'msg' => 'Không tìm thấy email trong hệ thống.']);
        } 

        $checkValidPassword = \Hash::check($data['password'], $customer->password);

        if (!$checkValidPassword) {
            return new JsonResponse (['status' => 0, 'msg' => 'Mật khẩu bạn nhập chưa đúng.']);
        }
        // Login
        Auth::guard('customer')->login($customer);

        // Login success
        return new JsonResponse (['status' => 1, 'msg' => 'Đăng nhập thành công.', 'customer' => $customer]);
    }

    /**
     * Send email to customer purchase
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Int $id User id
     * @return Response     
     */
    public function sendEmailToCustomerPurchase ($id)
    {
        $customer = CustomerModel::find($id);

        $orderModel = new OrderModel;
        $order = $orderModel->createNewOrder($customer->id);

        if ($order) {
            $status = MailService::sendEmailToCustomerPurchase($customer);
            if ($status) {
                $order->updateStatusForOrderDetail();
            }
        }

        // Delete cart
        destroyCart();

        return new JsonResponse (['status' => 1]);
    }
}
