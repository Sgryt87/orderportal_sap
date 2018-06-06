<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItImportResource extends JsonResource
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
            'nsn'                               => $this->nsn,
            'store_address'                     => $this->store_address,
            'store_city'                        => $this->store_city,
            'store_zip'                         => $this->store_zip,
            'restaurant_drive_thru_type'        => $this->restaurant_drive_thru_type,
            'coates_hardware_board_type'        => $this->coates_hardware_board_type,
            'coates_hardware_screen_protection' => $this->coates_hardware_screen_protection,
            'coates_hardware_number_of_screens' => $this->coates_hardware_number_of_screens,
            'coates_hardware_configuration'     => $this->coates_hardware_configuration,
            'coates_order_received_date'        => $this->coates_order_received_date,
            'coates_order_quote_submitted'      => $this->coates_order_quote_submitted,
            'coates_order_po_approved'          => $this->coates_order_po_approved,
            'coates_order_ship_date'            => $this->coates_order_ship_date
        ];
    }
}
