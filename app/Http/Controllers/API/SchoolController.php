<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\School;
use App\Bank;
class SchoolController extends Controller
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
        return School::with('Bank')->latest()->paginate(6);
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
            'email' => 'string|email|max:191|unique:schools',
            'name' => 'required|string|max:191|unique:schools'
        ]);

        return School::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'tel' => $request['tel'],
            'account_name' => $request['account_name'],
            'bank_id' => $request['bank_id'],
            'bank_branch' => $request['bank_branch'],
            'bank_number' => $request['bank_number'],
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
        $school = School::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191|unique:schools,name,'.$school->id,
            'email' => 'string|email|max:191|unique:schools,email,'.$school->id
        ]);

        $school->update($request->all());
        return ['message' => 'Update the school info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $school = School::findOrFail($id);

        // delete the school
        $school->delete();

        return['message' => 'SchoolDeleted'];
    }

    public function search(){
        if ($search = \Request::get('q')) {
            $schools = School::with('Bank')->where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $schools = School::with('Bank')->latest()->paginate(6);
        }
        return $schools;
    }
}
