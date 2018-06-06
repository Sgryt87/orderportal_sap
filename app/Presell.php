<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presell extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
