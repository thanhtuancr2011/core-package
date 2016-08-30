<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use App\Models\ProductModel;

class ProductFormRequest extends FormRequest
{
    protected $rules = [
        'name' => 'required',
        'alias' => 'unique:products,alias',
        'price' => 'required',
        'category_id' => 'required'
    ];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = $this->rules;

        $data = $this->all();

        if (!empty($data['id']))
        {
            $product = ProductModel::find($data['id']);

            if($product->alias == $data['alias']) {
                $rules = [ 
                    'name' => 'required',
                    'price' => 'required',
                    'category_id' => 'required'
                ]; 
            } 
        }
        
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'alias.unique' => 'Tên sản phẩm đã tồn tại trong hệ thống.',
            'price.required' => 'Bạn chưa nhập giá cho sản phẩm.',
            'category_id.required' => 'Bạn chưa chọn danh mục cho sản phẩm.'
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse(['status' => 0, 'errors' => $errors]);
    }
}
