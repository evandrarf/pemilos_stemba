<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidatePairRequest extends FormRequest
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
            'chairman_id' => ['required', 'exists:candidates,id', 'different:vice_chairman_id', Rule::unique('candidate_pairs', 'chairman_id')->ignore($this->id), Rule::unique('candidate_pairs', 'vice_chairman_id')->ignore($this->id)],
            'vice_chairman_id' => ['required', 'exists:candidates,id', 'different:chairman_id', Rule::unique('candidate_pairs', 'chairman_id')->ignore($this->id), Rule::unique('candidate_pairs', 'vice_chairman_id')->ignore($this->id)],
            'file' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'number' => [Rule::unique('candidate_pairs', 'number')->ignore($this->id), 'required', 'numeric']
        ];
    }

    public function messages()
    {
        return [
            'chairman_id.unique' => 'These candidates already exist',
            'vice_chairman_id.unique' => 'These candidates already exist',
            'chairman_id.different' => 'The chairman and vice chairman must be different',
            'vice_chairman_id.different' => 'The chairman and vice chairman must be different',
            'number.unique' => 'The candidate number has already been taken',
            'number.required' => 'The candidate number is required',
            'number.numeric' => 'The candidate number must be a number',
        ];
    }
}
