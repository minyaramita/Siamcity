<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table='banks';
    protected $fillable = ['bank_name'];
    public function school(){  
        return $this->hasMany('App/School','bank_id', 'id');
    }
}
