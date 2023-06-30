<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVotesRequest extends FormRequest
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
            'candidate_pair_id' => 'required|exists:candidate_pairs,id',
            'voter_id' => 'required|exists:voters,id|unique:votes,voter_id'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'candidate_pair_id.required' => 'Kandidat tidak boleh kosong',
            'candidate_pair_id.exists' => 'Kandidat tidak ditemukan',
            'voter_id.required' => 'Pemilih tidak boleh kosong',
            'voter_id.exists' => 'User tidak ditemukan',
            'voter_id.unique' => 'User sudah melakukan pemilihan'
        ];
    }
}
