<?php 
namespace Comus\Core\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Comus\Core\Models\CustomerModel;

class CustomerFormRequest extends FormRequest{
	protected $rules = [
		'fullname' => 'required',
		'email' 	 => 'required|unique:customers,email',
		'password' 	 => 'required'
	];

	public function rules()
    {
        $rules = $this->rules;

        /* Get all data input */
        $data  = $this->all();

        /* If update user */
        if (!empty($data['id'])){

        	/* Find user */
        	$user = CustomerModel::findOrFail($data['id']);

        	/* If email input = email of user edit */
        	if($user->email == $data['email']){
        		$rules = [
        			'email' => 'unique:customers,email,NULL,id,email,$user->email'
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
            'email.unique' => 'Email này đã tồn tại trong hệ thống.',
            'name.required' => 'Bạn chưa nhập tên.',
            'email.required' => 'Mời bạn nhập Email.',
            'password.required' => 'Bạn chưa nhập Password.'
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