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
            'chairman_id' => 'required|exists:candidates,id|different:vice_chairman_id',
            'vice_chairman_id' => 'required|exists:candidates,id|different:chairman_id',
            'file' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'vision' => 'nullable|string',
            'file_id' => 'nullable|exists:files,id',
            'mission' => 'nullable|string',
            'number' => [Rule::unique('candidate_pairs', 'number')->ignore($this->id), 'required', 'numeric']
        ];
    }
}
