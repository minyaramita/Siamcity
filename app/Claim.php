<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $table='claims';  //อ้างอิงกับชื่อตาราง
    protected $primaryKey = 'id';
    protected $fillable = ['ins_id', 'claim_date', 'accident_cause', 'accident_date', 'withdraw_amount', 'approve_amount',
                            'pay_date', 'payType', 'detail', 'status_id'];
    public function insurer(){
        return $this->belongsTo('App\Insurer', 'ins_id', 'id');
    } 

    public function status(){
        return $this->belongsTo('App\Status', 'status_id', 'id');
    } 
}
