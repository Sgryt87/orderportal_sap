<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItExport extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'nsn',
        'coates_hardware_board_type',
        'coates_hardware_screen_protection',
        'coates_hardware_number_of_screens',
        'coates_hardware_configuration',
        'coates_order_received_date',
        'coates_order_quote_submitted',
        'coates_order_po_approved',
        'coates_order_ship_date'
    ];
}
