<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Namelist extends Model
{
    protected $table='namelists';  //อ้างอิงกับชื่อตาราง
    protected $primaryKey = 'id';
    protected $fillable = ['school_id', 'quantity_student', 'quantity_personnel', 'receive_date', 'protection_date', 'plan_id', 'year','detail'];

    public function school(){
        return $this->belongsTo('App\School', 'school_id', 'id');
    }

    public function plan(){
        return $this->belongsTo('App\Plan', 'plan_id', 'id');
    }

    public function insurer(){  
        return $this->hasMany('App/Insurer','namelist_id', 'id');
    }
}
