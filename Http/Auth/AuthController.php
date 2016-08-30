<?php

namespace Comus\Core\Http\Auth;

use Validator;
use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use Comus\Core\Models\UserModel as User;
use Lang;
use Auth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    protected $loginPath = '/auth/login';

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    
    /**
     * Create a new authentication controller instance.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getLogout', 'autoLogin']]);
    }

    /**
     * Show the application login form.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return $this->showLoginForm();
    }

    /**
     * Show the application login form.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $view = property_exists($this, 'loginView') ? $this->loginView : 'auth.authenticate';

        if (view()->exists($view)) {
            return view($view);
        }

        $errors = [];

        return view('core::auth.login', compact('errors'));
    }

    /**
     * Get a validator for an incoming registration request.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email'    => 'required|email',
            'password' => 'required'
        ]);
    }

    /**
     * Post login.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Request $request
     */
    public function postLogin(Request $request)
    {
        /* Rule validate */
        $rules = [
            $this->loginUsername() => 'required', 
            'password' => 'required'
        ];

        /* Check validator */
        $v = $this->validator($request->all());

        if ($v->fails()) {
            $errors = $v->errors();
            return view('core::auth.login', compact('errors'));
        }
 
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        /* When user login failed */
        $errors = new MessageBag;
        $errors->add('login', $this->getFailedLoginMessage());

        return view('core::auth.login', compact('errors'));
    }

    /**
     * Auto login user
     * @author Thanh Tuan <thanhtuancr2011@gmail.com.com> 
     * @param string $email user's auto login
     * 
     */
    public function autoLogin($email){
        if($email != 'stop') {
            $user = User::where('email',$email)->first();
            if($user){
                if(!\Session::has('old_user')) {
                    \Session::put('old_user', \Auth::user() != null ? \Auth::user()->email : 'stop');
                } else if(\Session::get('old_user') == $email){
                    // Stop being user
                    \Session::forget('old_user');
                }
                // Login
                \Auth::login($user);
            }else{
                // If user not in system
                \Session::flash('auto-login', 'This account doesn\'t exist in the system');
            }
            return redirect('/');
        }    
    }

}
