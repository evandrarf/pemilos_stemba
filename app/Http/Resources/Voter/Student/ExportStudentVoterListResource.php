<?php

namespace App\Http\Resources\Voter\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class ExportStudentVoterListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($data)
    {
        return [
            'name' => $this->name,
            'class' => $this->class,
            'username' => $this->username,
            'password' => $this->password,
            'status' => $this->status ? 'sudah' : 'belum',
        ];
    }

    // private function transformData($data)
    // {
    // }

    // private function transformCollection($collection)
    // {
    //     return $collection->transform(function ($data) {
    //         return $this->transformData($data);
    //     });
    // }
}
