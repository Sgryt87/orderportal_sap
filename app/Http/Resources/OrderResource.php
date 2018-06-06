<?php

namespace App\Http\Resources;

use App\Http\Resources\OrderBoardResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                                => $this->id,
            'nsn'                               => $this->nsn,
            'presell'                           => $this->presell,
            'order_board'                       => $this->order_board,
            'protective_cover'                  => $this->protective_cover,
            'height_requirement'                => $this->height_requirement,
            'delivery_note'                     => $this->delivery_note,
            'note'                              => $this->note,
            'requested_enclosure_delivery_date' => $this->requested_enclosure_delivery_date,
            'ship_date'                         => $this->ship_date
        ];
    }
}
