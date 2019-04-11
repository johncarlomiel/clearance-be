<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        return DB::table('events')
        ->where('ay_id',$id)
        ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
            //Get all students from sent ay_id
            $students = DB::table('acad_year_students')
            ->where('ay_id','=',$request->ay_id)
            ->get();

            $event_id = DB::table('events')->insertGetId(
            [
                'name' => $request->name,
                'amount' => $request->amount,
                'ay_id' => $request->ay_id,
                'term' => $request->term
            ]
            );
            $students_with_events = [];
            foreach ($students as $key => $value) {
                array_push($students_with_events,
                [
                    "ay_id" => $request->ay_id,
                    "std_id" => $value->ay_student_id,
                    "event_id" => $event_id,
                    "balance" => $request->amount
                ]);
            }
            DB::table('student_events')->insert($students_with_events);
            DB::commit();
            return DB::table('events')
            ->where('ay_id',$request->ay_id)
            ->get();
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            return response()->json(["message" => "Process Overload"]);
        }
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

    public function report(Request $request,$ay_id){
        

        $res =  DB::table('acad_year_students')
                ->where('acad_year_students.ay_id',$ay_id)
                ->join('students','acad_year_students.std_id', '=','students.id')
                ->where('students.course', '=', $request->course)
                ->orderBy('students.course','asc')
                ->orderBy('students.year','asc')
                ->orderBy('students.name', 'asc')
                ->get();

        foreach ($res as $key => $value) {
            # code...   
            // return response()->json($value->ay_student_id);

            $events = DB::table('student_events')
            ->where('student_events.std_id', '=',$value->ay_student_id)
            ->join('events', 'student_events.event_id', '=','events.event_id')
            ->get();

            $res[$key]->events = $events;

        }
                
        return response()->json($res);
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
        DB::table('events')
            ->where('event_id', $id)
            ->update(['name' => $request->name, 'amount' => $request->amount, 'term' => $request->term]);
            return DB::table('events')
            ->where('ay_id',$request->ay_id)
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
        DB::table('events')->where('event_id', '=', $id)->delete();
        return DB::table('events')
        ->where('ay_id',$request->ay_id)
        ->get();
    }
}
