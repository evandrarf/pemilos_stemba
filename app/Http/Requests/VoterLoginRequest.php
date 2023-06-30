<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoterLoginRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => 'in:teacher,student',
            'username' => 'required',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'The username is required',
            'password.required' => 'The password is required'
        ];
    }
}
