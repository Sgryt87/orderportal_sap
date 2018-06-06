<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model

{
    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'nsn',
        'presell_id',
        'order_board_id',
        'protective_cover_id',
        'height_requirement_id',
        'delivery_note',
        'requested_enclosure_delivery_date',
        'import_date',
        'ship_date'
    ];

    public function it_import(){
        return $this->hasOne('App\ItImport');
    }

    public function presell()
    {
        return $this->belongsTo('App\Presell');
    }

    public function order_board()
    {
        return $this->belongsTo('App\OrderBoard');
    }

    public function protective_cover()
    {
        return $this->belongsTo('App\ProtectiveCover');
    }

    public function height_requirement()
    {
        return $this->belongsTo('App\HeightRequirement');
    }
}
