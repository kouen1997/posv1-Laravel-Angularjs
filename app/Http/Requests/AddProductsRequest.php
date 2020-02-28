<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProductsRequest extends FormRequest
{
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
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2000',
            'name' => 'required',
            'type' => 'required',
            'brand' => 'required',
            'parent_category' => 'required',
            'unit' => 'required',
            'qty' => 'required',
            'cost' => 'required',
            'price' => 'required'
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Product name is required',
            'type.required' => 'Product type is required',
            'brand.required' => 'Brand is required',
            'parent_category.required' => 'Category is required',
            'unit.required' => 'Product unit is required',
            'qty.required' => 'Product quantity is required',
            'cost.required' => 'Product cost is required',
            'price.required' => 'Product price is required',
            'image.mimes' => 'Product image must be in JPEG, JPG, PNG format only',
            'image.max' => 'Product image can only accept lower than 2mb'
        ];
    }
}
