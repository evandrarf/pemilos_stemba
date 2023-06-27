<?php

namespace App\Http\Resources;

use App\Actions\Utility\GetFile;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CandidatePairListResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->transformCollection($this->collection),
            'meta' => [
                "success" => true,
                "message" => "Success get all candidate pair lists",
                'pagination' => $this->metaData()
            ]
        ];
    }

    private function transformData($data)
    {
        $getFile = new GetFile();

        return [
            'id' => $data->id,
            'chairman' => $data->chairman,
            'vice_chairman' => $data->vice_chairman,
            'vision' => $data->vision ?? '',
            'mission' => $data->mission ?? '',
            'number' => $data->number,
            'image' => $data->image ? $getFile->handle($data->image)->full_path : asset('img/default.png'),
        ];
    }

    private function transformCollection($collection)
    {
        return $collection->transform(function ($data) {
            return $this->transformData($data);
        });
    }

    private function metaData()
    {
        return [
            "total" => $this->total(),
            "count" => $this->count(),
            "per_page" => (int)$this->perPage(),
            "current_page" => $this->currentPage(),
            "total_pages" => $this->lastPage(),
            "links" => [
                "next" => $this->nextPageUrl()
            ],
        ];
    }
}
