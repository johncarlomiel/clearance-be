<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StudentApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        
        if(!empty($request->excludedStudents)){
            return DB::table('students')
                    ->whereNotIn('id',json_decode($request->excludedStudents))
                    ->get();
        }else{
            return DB::table('students')->get();

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $inserted = DB::table('students')->insert(
            ['id' => $request->id, 'name' => "$request->name", 'course' => "$request->course", 'year' => "$request->year"]
        );
        return response()->json(["message" => "Student Added"]);
        
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
        return DB::table('acad_year_students')
                    ->where('ay_student_id','=',$id)
                    ->join('students','acad_year_students.std_id','=','students.id')
                    ->get();
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
        //
        DB::table('students')
            ->where('id', $id)
            ->update(['name' => $request->name, 'course' => $request->course, 'year' => $request->year, 'id' => $request->id]);
        return response()->json(["message" => "Student Updated"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        DB::table('students')->where('id', $id)->delete();
        return response()->json(["message"=> "Student with id of ".$id." is removed"]);
    }
}
