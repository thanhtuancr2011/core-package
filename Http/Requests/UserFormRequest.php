<?php 
namespace Comus\Core\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Comus\Core\Models\UserModel;

class UserFormRequest extends FormRequest{
	protected $rules = [
		'first_name' => 'required',
		'last_name'  => 'required',
		'email' 	 => 'required|unique:users,email'
	];

	public function rules()
    {
        $rules = $this->rules;

        /* Get all data input */
        $data  = $this->all();

        /* If update user */
        if (!empty($data['id'])){

        	/* Find user */
        	$user = UserModel::findOrFail($data['id']);

        	/* If email input = email of user edit */
        	if($user->email == $data['email']){
        		$rules = [
        			'email' => 'unique:users,email,NULL,id,email,$user->email'
				];
        	}
        }
        return $rules;
    }

	public function authorize()
	{
		return true;
	}

	public function messages()
    {
        return [
            'email.unique' => 'Email existing in the system.',
            'first_name.required' => 'First name is a required field.',
            'last_name.required' => 'Last name is a required field.',
            'email.required' => 'Email is a required field.',
            'password.required' => 'Password is a required field.'
        ];
    }

	/**
	 * Get the proper failed validation response for the request.
	 *
	 * @param  array  $errors
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function response(array $errors)
	{
		return new JsonResponse(['status'=>0, 'error'=>$errors]);
	}
}