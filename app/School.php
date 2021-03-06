<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table='schools';  //อ้างอิงกับชื่อตาราง
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'email', 'tel','account_name','bank_id','bank_branch','bank_number'];
    public function bank(){
        return $this->belongsTo('App\Bank', 'bank_id', 'id');
    }
    public function school(){
        return $this->hasMany('App\Namelist', 'school_id', 'id');
    }
}
