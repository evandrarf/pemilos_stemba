<?php

namespace App\Http\Resources;

use App\Actions\Utility\GetFile;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidatePairDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $getFile = new GetFile();

        return [
            'id' => $this->id,
            'chairman' => $this->chairman->only('name', 'nis', 'class'),
            'vice_chairman' => $this->vice_chairman->only('name', 'nis', 'class'),
            'number' => $this->number,
            'vision' => $this->vision,
            'mission' => $this->mission,
            'image' => $this->image ? $getFile->handle($this->image)->full_path : asset('img/default.png'),
        ];
    }
}
