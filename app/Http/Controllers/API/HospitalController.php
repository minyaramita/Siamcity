<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Hospital;
class HospitalController extends Controller
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
        return Hospital::latest()->paginate(5);
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
            'email' => 'string|email|max:191|unique:hospitals',
            'name' => 'required|string|max:191|unique:hospitals'
        ]);

        return Hospital::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'tel' => $request['tel'],
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
        $hospital = Hospital::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191|unique:hospitals,name,'.$hospital->id,
            'email' => 'string|email|max:191|unique:hospitals,email,'.$hospital->id
        ]);

        $hospital->update($request->all());
        return ['message' => 'Update the hospital info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hospital = Hospital::findOrFail($id);

        // delete the hospital
        $hospital->delete();

        return['message' => 'HospitalDeleted'];
    }

    public function search(){
        if ($search = \Request::get('q')) {
            $hospitals = Hospital::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $hospitals = Hospital::latest()->paginate(5);
        }
        return $hospitals;
    }
}
