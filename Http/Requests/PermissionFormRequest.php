<?php 

namespace Comus\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Comus\Core\Models\PermissionModel;

class PermissionFormRequest extends FormRequest {

    protected $rules = [
        'name' => 'required',
        'display_name' => 'required',
        'slug' => 'required|unique:permissions,slug'
    ];

    public function rules()
    {
        $rules = $this->rules;

        /* Get all data input */
        $data  = $this->all();

        /* If update permission */
        if (!empty($data['id'])){

            /* Find permission */
            $permission = PermissionModel::findOrFail($data['id']);

            /* If slug input = slug of permission edit */
            if($permission->slug == $data['slug']){
                $rules = [
                    'slug' => 'unique:permissions,slug,NULL,id,slug,$permission->slug'
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
            'slug.unique' => 'Permission existing in the system.',
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