<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'name', 'email', 'tell','account_name','bank_id','bank_branch','bank_number'
    ];
}
