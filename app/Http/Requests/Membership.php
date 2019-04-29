<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Membership extends FormRequest
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
            'name'=>['required', 'max:20', 'string'],
            'email'=>['required', 'max:255', 'email'],
            'password'=>['required', 'max:30']
        ];
    }
}
