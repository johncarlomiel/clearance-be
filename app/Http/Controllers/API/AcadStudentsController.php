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
        return DB::table('acad_year_students')
                    ->where('ay_id', $request->ay_id)
                    ->join('students', 'acad_year_students.std_id','=','students.id')
                    ->select('students.*','acad_year_students.ay_student_id')
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
        //Get all Events in the db

        DB::beginTransaction();

        try {
            $events = DB::table('events')
            ->where('ay_id', $request->all()["data"][0]["ay_id"])
            ->get();

            $students = [];
            $students_with_events = [];
            foreach ($request->all()["data"] as $key => $value) {
                # code...
                $ay_stud_id = DB::table('acad_year_students')->insertGetId(
                    ['std_id' => $value["std_id"], 'ay_id' => $value["ay_id"]]
                );
                // return response()->json(["id" => $ay_stud_id]);
                // array_push($students, ['std_id' => $value["std_id"], 'ay_id' => $value["ay_id"]]);
                foreach ($events as $eventKey => $eventVal) {
                    # code...
                    array_push($students_with_events,
                    [
                    "ay_id" => $value["ay_id"],
                     "std_id" => $ay_stud_id,
                      "event_id" => $eventVal->event_id,
                      "balance" => $eventVal->amount
                    ]);
                }
            }
            // DB::table('acad_year_students')->insert($students);
            DB::table('student_events')->insert($students_with_events);
            
            
            DB::commit();
            return response()->json(["message" => "Inserted"]);
        } catch (\Exception $e) {

            DB::rollback();
            return response()->json(["message" => "There is an error on the process"]);
            //throw $th;
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
        return response()->json(["message" => "Updated"]);
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
        DB::table('acad_year_students')
        ->where('ay_student_id','=',$id)
        ->delete();
        return response()->json(["message" => "Deleted"]);
    }
}
