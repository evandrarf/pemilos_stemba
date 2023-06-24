<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateStudentVoterRequest extends FormRequest
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
            'name' => 'required|string',
            'class' => 'required|string',
            'username' => ['required', 'numeric', 'string', Rule::unique('voters', 'username')->where('type', 'student')],
            'password' => 'string|min:8|nullable',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'class.required' => 'Class is required',
            'username.required' => 'Username is required',
            'username.numeric' => 'Username must be numeric',
            'username.unique' => 'Username already exists',
            'password.min' => 'Password must be at least 8 characters',
        ];
    }
}
