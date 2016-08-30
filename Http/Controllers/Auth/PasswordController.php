<?php 

namespace Comus\Core\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\MessageBag;
use Validator;

class PasswordController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/

	/**
	 * Create a new password controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\PasswordBroker  $passwords
	 * @return void
	 */
	public function __construct(Guard $auth, PasswordBroker $passwords)
	{
		$this->auth = $auth;
		$this->passwords = $passwords;
	}

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * The password broker implementation.
	 *
	 * @var PasswordBroker
	 */
	protected $passwords;

	/**
	 * Display the form to request a password reset link.
	 * @author Thanh Tuan <thanhtuancr2011@gmail.com>
	 * @return Response
	 */
	public function getEmail()
	{
		$errors = [];
		return view('core::users.reset-password', compact('errors'));
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
            'email'    => 'required|email'
        ]);
    }

    /**
     * Get the failed reset password message.
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @return string
     */
    protected function getFailedResetPasswordMessage()
    {
        return 'We can\'t find a user with that e-mail address.';
    }

	/**
	 * Send a reset link to the given user.
	 * @author Thanh Tuan <thanhtuancr2011@gmail.com>
	 * @param  Request  $request
	 * @return Response
	 */
	public function postEmail(Request $request)
	{
		/* Rule validate */
        $rules = ['email' => 'required|email'];

        /* Check validator */
        $v = $this->validator($request->all());

        if ($v->fails()) {
            $errors = $v->errors();
            return view('core::users.reset-password', compact('errors'));
        }

		$response = $this->passwords->sendResetLink($request->only('email'), function($m)
		{
			$m->subject($this->getEmailSubject());
		});
		
		switch ($response)
		{
			case PasswordBroker::RESET_LINK_SENT:
				return redirect()->back()->with('status', trans($response));
			
			case PasswordBroker::INVALID_USER:
		        $errors = new MessageBag;
		        $errors->add('login', $this->getFailedResetPasswordMessage());
				return view('core::users.reset-password', compact('errors'));
		}
	}

	/**
	 * Get the e-mail subject line to be used for the reset link email.
	 *
	 * @return string
	 */
	protected function getEmailSubject()
	{
		return isset($this->subject) ? $this->subject : 'Your Password Reset Link';
	}

	/**
	 * Display the password reset view for the given token.
	 * @author Thanh Tuan <thanhtuancr2011@gmail.com>
	 * @param  string  $token
	 * @return Response
	 */
	public function getReset($token = null)
	{
		$user = \Auth::user();
		if(!empty($user->id)){
			$this->auth->logout();
		}
		if(empty($user->id)){

		}
		if (is_null($token))
		{
			throw new NotFoundHttpException;
		}

		return view('users::auth.reset')->with('token', $token);
	}
	
	/**
	 * Display the password reset view for the given token.
	 * @author Thanh Tuan <thanhtuancr2011@gmail.com>
	 * @param  string  $token
	 * @return Response
	 */
	public function getCreatePassword($token = null)
	{
		if (is_null($token))
		{
			throw new NotFoundHttpException;
		}

		return view('users::auth.createPassword')->with('token', $token);
	}

	/**
	 * Reset the given user's password.
	 * @author Thanh Tuan <thanhtuancr2011@gmail.com>
	 * @param  Request  $request
	 * @return Response
	 */
	public function postReset(Request $request)
	{
		$this->validate($request, [
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|confirmed',
		]);

		$credentials = $request->only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = $this->passwords->reset($credentials, function($user, $password)
		{
			$user->password = bcrypt($password);

			$user->save();

			$this->auth->login($user);
		});
		switch ($response)
		{
			case PasswordBroker::PASSWORD_RESET:
				return redirect('/');

			default:
				return redirect()->back()
							->withInput($request->only('email'))
							->withErrors(['email' => trans($response)]);
		}
	}

	/**
	 * Get the post register / login redirect path.
	 * @author Thanh Tuan <thanhtuancr2011@gmail.com>
	 * @return string
	 */
	public function redirectPath()
	{
		if (property_exists($this, 'redirectPath'))
		{
			return $this->redirectPath;
		}

		return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
	}

}
