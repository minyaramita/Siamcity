<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Namelist;
class NamelistController extends Controller
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
        return Namelist::with(['School','Plan'])->latest()->paginate(10);
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
            'school_id' => 'required',
            'protection_date' => 'required',
            'plan_id' => 'required',
            'year' => 'required',
        ]);

        return Namelist::create([
            'school_id' => $request['school_id'],
            'quantity_student' => $request['quantity_student'],
            'quantity_personnel' => $request['quantity_personnel'],
            'receive_date' => $request['receive_date'],
            'protection_date' => $request['protection_date'],
            'plan_id' => $request['plan_id'],
            'year' => $request['year'],
            'detail' => $request['detail'],
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
        $namelist = Namelist::findOrFail($id);

        $this->validate($request,[
            'school_id' => 'required',
            'protection_date' => 'required',
            'plan_id' => 'required',
            'year' => 'required',
        ]);

        $namelist->update($request->all());
        return ['message' => 'Update the namelist info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $namelist = Namelist::findOrFail($id);

        // delete the namelist
        $namelist->delete();

        return['message' => 'NamelistDeleted'];
    }

    public function search(){
        if ($search = \Request::get('q')) {
            $namelists = Namelist::with(['School','Plan'])->where(function($query) use ($search){
                $query->where('id','LIKE',"%$search%")
                        ->orWhere('year','LIKE',"%$search%")
                        ->orWhereHas('School',function($query) use ($search){
                            $query->where('name','LIKE',"%$search%");
                        });
            })->paginate(20);
        }else{
            $namelists = Namelist::with(['School','Plan'])->latest()->paginate(6);
        }
        return $namelists;
    }
}
