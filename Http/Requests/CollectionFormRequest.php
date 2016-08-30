<?php 

namespace Comus\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Comus\Core\Models\CollectionModel;

class CollectionFormRequest extends FormRequest {

    protected $rules = [
        'title' => 'required',
        'content' => 'required',
        'alias_title' => 'unique:collections,alias_title'
    ];

    public function rules()
    {
        $rules = $this->rules;

        /* Get all data input */
        $data  = $this->all();

        /* If update collection */
        if (!empty($data['id'])){

            /* Find collection */
            $collection = CollectionModel::findOrFail($data['id']);

            /* If alias_title input = alias_title of collection edit */
            if($collection->alias_title == $data['alias_title']){
                $rules = [
                    'alias_title' => 'unique:collections,alias_title,NULL,id,alias_title,$collection->alias_title'
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
            'title.required' => 'Title is a required field.',
            'content.required' => 'Content is a required field.',
            'alias_title.unique' => 'Collection title existing in the system.',
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