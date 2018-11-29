<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class status extends Model
{
    protected $table='statuses';
    protected $fillable = ['name'];
    public function claim(){  
        return $this->hasMany('App/Claim','status_id', 'id');
    }
}
