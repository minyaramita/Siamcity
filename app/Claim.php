<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $table='claims';  //อ้างอิงกับชื่อตาราง
    protected $primaryKey = 'id';
    protected $fillable = ['ins_id', 'claim_date', 'accident_cause', 'accident_date', 'withdraw_amount', 'approve_amount',
                            'pay_datet', 'payType', 'detail', 'status'];
}
