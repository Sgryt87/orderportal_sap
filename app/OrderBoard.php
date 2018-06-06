<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBoard extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    public function orders(){
        return $this->belongsTo('App\Order');
    }
}
