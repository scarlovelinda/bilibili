<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:users,name|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/',
            'password' => 'required|alpha_num|between:6,16|confirmed',
            'password_confirmation' => 'required|alpha_num|between:6,16',
            'captcha' => 'required|string'
        ];
    }
}
