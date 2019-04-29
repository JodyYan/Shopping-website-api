<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShoppingRequest extends FormRequest
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
            'name'=>['required', 'max:20'],
            'describe'=>['required', 'max:255'],
            'price'=>['required', 'max:10', 'integer'],
            'quantity'=>['required', 'max:10', 'integer'],
            'image'=>['required', 'image']
        ];
    }
}
