<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table='banks';
    
    public function school(){
            
        return $this->hasMany(School::class);
        
    }
}
