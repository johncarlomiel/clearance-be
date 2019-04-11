<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request){
        $response = DB::table('users')
        ->where([
            ['type','=', 'admin'],
            ['username','=', $request->username],
            ['pwd','=', $request->pwd],
        ])
        ->get();
        $payload = ["user_id" => $response[0]->user_id,"type"=> $response[0]->type];

        return response()->json(["jwt" => $this->createJWT($payload)]);

    }
    public function store(Request $request)
    {
        //
    }

    private function createJWT($payload){
        $header = $this->base64_url_encode(json_encode(["alg"=>"HS256", "typ" => "JWT"]));
        $payload = $this->base64_url_encode(json_encode($payload));
        return $header.".".$payload.".".$this->base64_url_encode(hash_hmac('sha256', "$header.$payload", "averylongpassword123!@#", true));
    }

    private function base64_url_encode($input) {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }
    private function base64_url_decode($input) {
    return base64_decode(strtr($input, '._-', '+/='));
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
        //
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
    }
}
