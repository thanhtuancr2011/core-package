<?php

namespace Comus\Core\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Comus\Core\Services\SocialAccountService;
use App\Http\Controllers\Controller;
use Socialite;
use Auth;

class SocialAuthController extends Controller
{
	public function index()
	{
		return view('auth.login');
	}

    /**
     * Connect to api facebook
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Void
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();   
    }   

    /**
     * Call to service create or login user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return Redirect
     */
    public function callback(SocialAccountService $services, $provider)
    {
    	$customer = $service->createOrGetUser(Socialite::driver($provider)->user(), $provider);
        
        Auth::guard('customer')->login($customer);

        return redirect()->to('/');
    }
}
