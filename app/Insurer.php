<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insurer extends Model
{
    protected $table='insurers';  //อ้างอิงกับชื่อตาราง
    protected $primaryKey = 'id';
    protected $fillable = ['title_id', 'ins_fname', 'ins_lname', 'ins_class', 'namelist_id', 'ins_type'];
    public function title(){
        return $this->belongsTo('App\Title', 'title_id', 'id');
    }
    public function namelist(){
        return $this->belongsTo('App\Namelist', 'namelist_id', 'id');
    }
}
