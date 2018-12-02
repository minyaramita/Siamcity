<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Insurer; 

class InsurerController extends Controller
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
        return Insurer::with(['Title','Namelist.School','Namelist.Plan'])->latest()->paginate(5);
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
            'title_id' => 'required',
            'ins_fname' => 'required|string|max:191',
            'ins_lname' => 'required|string|max:191',
            'namelist_id' => 'required',
            'ins_type' => 'required',
        ]);

        return Insurer::create([
            'title_id' => $request['title_id'],
            'ins_fname' => $request['ins_fname'],
            'ins_lname' => $request['ins_lname'],
            'ins_class' => $request['ins_class'],
            'namelist_id' => $request['namelist_id'],
            'ins_type' => $request['ins_type'],
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
        $insurer = Insurer::findOrFail($id);

        $this->validate($request,[
            'title_id' => 'required',
            'ins_fname' => 'required|string|max:191',
            'ins_lname' => 'required|string|max:191',
            'namelist_id' => 'required',
            'ins_type' => 'required',
        ],[
            'title_id.required' => 'กรุณากรอกคำนำหน้าชื่อ',
            'ins_fname.required' => 'กรุณากรอกชื่อจริง',
            'ins_lname.required' => 'กรุณากรอกนามสกุล',
            'namelist_id.required' => 'กรุณาเลือกรหัสการรับรายชื่อผู้ทำประกัน',
            'ins_type.required' => 'กรุณาเลือกประเภทผู้ทำประกัน',
        ]);

        $insurer->update($request->all());
        return ['message' => 'Update the insurer info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $insurer = Insurer::findOrFail($id);

        // delete the insurer
        $insurer->delete();

        return['message' => 'InsurerDeleted'];
    }

    public function search(){
        if ($search = \Request::get('q')) {
            $insurers = Insurer::with(['Title','Namelist.School','Namelist.Plan'])->where(function($query) use ($search){
                $query->where('ins_fname','LIKE',"%$search%")
                        ->orWhere('ins_lname','LIKE',"%$search%")
                        ->orWhereHas('Namelist',function($query) use ($search){
                            $query->where('year','LIKE',"%$search%")
                            ->orWhereHas('School',function($query) use ($search){
                                $query->where('name','LIKE',"%$search%");
                            }); 
                        });  
            })->paginate(20);
        }else{
            $insurers = Insurer::with(['Title','Namelist.School','Namelist.Plan'])->latest()->paginate(5);
        }
        return $insurers;
    }
}
