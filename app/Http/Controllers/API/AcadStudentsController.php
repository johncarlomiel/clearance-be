<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AcadStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
            
            return DB::table('acad_year_students')
                            ->join('students', 'acad_year_students.std_id','=','students.id')
                            ->where('ay_id', $request->ay_id)
                            ->where('students.name','like',"%$request->keyword%")
                            ->orWhere('students.id','like',"%$request->keyword%")
                            // ->where([
                            //     ['students.id','like',"%$request->keyword%"],
                            //     ['students.name','like',"%$request->keyword%"]
                            // ])
                            ->get();
          
        }else{
            return DB::table('acad_year_students')
                        ->where('ay_id', $request->ay_id)
                        ->join('students', 'acad_year_students.std_id','=','students.id')
                        ->select('students.*','acad_year_students.ay_student_id')
                        ->get();
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
        //Get all Events in the db
        DB::table('acad_year_students')->insert($request->data);
        $noEntryOnEventStds = DB::select('select * from acad_year_students where ay_student_id NOT IN (select std_id FROM student_events WHERE ay_id = '.$request->data[0]["ay_id"].')');
        $acadStds =  DB::table('acad_year_students')
        ->where('ay_id', $request->data[0]["ay_id"])
        ->join('students', 'acad_year_students.std_id','=','students.id')
        ->select('students.*','acad_year_students.ay_student_id')
        ->get();
        return response()->json(
            ["noEntryOnEventStds" => $noEntryOnEventStds, "acad_students" => $acadStds]
        );

    }
    public function storeEvents(Request $request){
        DB::table('student_events')->insert($request->container);
        return response()->json(["message" => "Updated"]);

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
        return DB::table('student_events')
                    ->where('std_id', $id)
                    ->join('events', 'student_events.event_id', '=','events.event_id')
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
        DB::table('student_events')
            ->where('student_events_id', $id)
            ->update(["paid" => DB::raw('student_events.paid +'.$request->amount),
             "balance" => DB::raw('student_events.balance -'.$request->amount)]);
        return DB::table('student_events')
             ->where('std_id', $request->studentEventInfo["std_id"])
             ->join('events', 'student_events.event_id', '=','events.event_id')
             ->get();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        //
        DB::table('acad_year_students')
        ->where('ay_student_id','=',$id)
        ->delete();
        return DB::table('acad_year_students')
                        ->where('ay_id', $request->ay_id)
                        ->join('students', 'acad_year_students.std_id','=','students.id')
                        ->select('students.*','acad_year_students.ay_student_id')
                        ->get();
    }

 
}
