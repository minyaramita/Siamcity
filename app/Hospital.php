<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $table='hospitals';  //อ้างอิงกับชื่อตาราง
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'email', 'tel'];
}
