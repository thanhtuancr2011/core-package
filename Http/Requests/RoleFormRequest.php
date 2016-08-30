<?php 

namespace Comus\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Comus\Core\Models\RoleModel;

class RoleFormRequest extends FormRequest {

    protected $rules = [
        'name' => 'required',
        'display_name' => 'required',
        'slug' => 'required|unique:roles,slug'
    ];

    public function rules()
    {
        $rules = $this->rules;

        /* Get all data input */
        $data  = $this->all();

        /* If update role */
        if (!empty($data['id'])){

            /* Find role */
            $role = RoleModel::findOrFail($data['id']);

            /* If slug input = slug of role edit */
            if($role->slug == $data['slug']){
                $rules = [
                    'slug' => 'unique:roles,slug,NULL,id,slug,$role->slug'
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
            'name.required' => 'Name is a required field.',
            'display_name.required' => 'Display name is a required field.',
            'slug.unique' => 'Role existing in the system.',
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