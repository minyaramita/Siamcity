<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    protected $table='titles';
    protected $fillable = ['name'];
    public function insurer(){  
        return $this->hasMany('App/Insurer','title_id', 'id');
    }
}