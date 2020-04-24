<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Claim;

class ClaimController extends Controller
{
     /**
     * Create a new controller instance.
     * ป้องกันการเข้าถึงข้อมูล api โดยไม่ได้ log in
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Claim::with(['Insurer.Namelist.School','Insurer.Namelist.Plan','Insurer.Title','Status'])->latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'ins_id' => 'required',
            'accident_cause' => 'required|string|max:191',
            'withdraw_amount' => 'required|numeric|min:1',
            'approve_amount' => 'required|numeric|min:0',
            'status_id' => 'required',
        ]);

        return Claim::create([
            'ins_id' => $request['ins_id'],
            'claim_date' => $request['claim_date'],
            'accident_cause' => $request['accident_cause'],
            'accident_date' => $request['accident_date'],
            'withdraw_amount' => $request['withdraw_amount'],
            'approve_amount' => $request['approve_amount'],
            'pay_date' => $request['pay_date'],
            'payType' => $request['payType'],
            'detail' => $request['detail'],
            'status_id' => $request['status_id'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $claim = Claim::findOrFail($id);
        $this->validate($request,[
            'ins_id' => 'required',
            'accident_cause' => 'required|string|max:191',
            'withdraw_amount' => 'required|numeric|min:1',
            'approve_amount' => 'required|numeric|min:0',
            'status_id' => 'required',
        ]);

        $claim->update($request->all());
        return ['message' => 'Update the claim info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $claim = Claim::findOrFail($id);

        // delete the insurer
        $claim->delete();

        return['message' => 'ClaimDeleted'];
    }

    public function search(){
        if ($search = \Request::get('q')) {
            $claims = Claim::with(['Status','Insurer.Namelist.School','Insurer.Namelist.Plan','Insurer.Title'])->where(function($query) use ($search){
                $query->where('payType','LIKE',"%$search%")
                        ->orWhere('accident_cause','LIKE',"%$search%")
                        ->orWhere('withdraw_amount','LIKE',"%$search%")
                        ->orWhere('approve_amount','LIKE',"%$search%")
                        ->orWhereHas('Insurer',function($query) use ($search){
                            $query->where('ins_fname','LIKE',"%$search%")
                            ->orWhere('ins_lname','LIKE',"%$search%")
                            ->orWhere('ins_class','LIKE',"%$search%")
                            ->orWhere('ins_type','LIKE',"%$search%")
                            ->orWhereHas('Namelist',function($query) use ($search){
                                $query->where('year','LIKE',"%$search%")
                                ->orWhereHas('School',function($query) use ($search){
                                    $query->where('name','LIKE',"%$search%");                                          
                                }); 
                            });                 
                        });                 
            })->paginate(20);
        }else{
            $claims = Claim::with(['Insurer.Namelist.School','Insurer.Namelist.Plan','Insurer.Title','Status'])->latest()->paginate(10);
        }
        return $claims;   
    }
}
