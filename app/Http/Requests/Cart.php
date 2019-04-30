<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Cart extends FormRequest
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
            'seller_id'=>['required','exists:sellers,id'],
            'receiver_name'=>['required', 'max:20', 'string'],
            'receiver_email'=>['required', 'max:255', 'email'],
            'receiver_phone'=>['required', 'max:15', 'string'],
            'address'=>['required', 'max:255','string']
        ];
    }
}
