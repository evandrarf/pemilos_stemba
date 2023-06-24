<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentVoterRequest extends FormRequest
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
            'username' => ['required', 'numeric', 'string', 'unique:voters,username,' . $this->id . ',id,type,student'],
            'password' => 'string|min:8|nullable',
        ];
    }
}
