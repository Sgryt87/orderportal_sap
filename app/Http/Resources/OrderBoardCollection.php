<?php

namespace App\Http\Resources;

use App\Http\Resources\OrderBoardResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderBoardCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => OrderBoardResource::collection($this->collection)
        ];
    }
}
