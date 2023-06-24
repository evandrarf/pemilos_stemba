<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCandidatePairRequest extends FormRequest
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
            'chairman_id' => 'required|exists:candidates,id|different:vice_chairman_id|unique:candidate_pairs,chairman_id|unique:candidate_pairs,vice_chairman_id',
            'vice_chairman_id' => 'required|exists:candidates,id|different:chairman_id|unique:candidate_pairs,chairman_id|unique:candidate_pairs,vice_chairman_id',
            'file' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'number' => 'required|integer|unique:candidate_pairs,number'
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
        ];
    }
}
