<?php 

namespace Comus\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Comus\Core\Models\ArticleModel;

class ArticleFormRequest extends FormRequest {

    protected $rules = [
        'title' => 'required',
        'content' => 'required',
        'alias_title' => 'unique:articles,alias_title'
    ];

    public function rules()
    {
        $rules = $this->rules;

        /* Get all data input */
        $data  = $this->all();

        /* If update article */
        if (!empty($data['id'])){

            /* Find article */
            $article = ArticleModel::findOrFail($data['id']);

            /* If alias_title input = alias_title of article edit */
            if($article->alias_title == $data['alias_title']){
                $rules = [
                    'alias_title' => 'unique:articles,alias_title,NULL,id,alias_title,$article->alias_title'
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
            'alias_title.unique' => 'Article title existing in the system.',
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