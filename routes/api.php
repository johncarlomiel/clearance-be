<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('acad-year', 'API\AcadYearController@store')->middleware('cors');
Route::get('acad-year', 'API\AcadYearController@index')->middleware('cors');
Route::get('acad-year/{id}', 'API\AcadYearController@show')->middleware('cors');


Route::post('student', 'API\StudentApiController@store')->middleware('cors');
Route::patch('student/{id}', 'API\StudentApiController@update')->middleware('cors');
Route::get('student', 'API\StudentApiController@index')->middleware('cors');
Route::delete('student/{id}', 'API\StudentApiController@destroy')->middleware('cors');
Route::get('student/{id}','API\StudentApiController@show')->middleware('cors');



Route::post('event', 'API\EventController@store')->middleware('cors');
Route::get('event/{id}', 'API\EventController@index')->middleware('cors');
Route::delete('event/{id}', 'API\EventController@destroy')->middleware('cors');
Route::patch('event/{id}', 'API\EventController@update')->middleware('cors');

Route::get('acad-students','API\AcadStudentsController@index')->middleware('cors');
Route::post('acad-students', 'API\AcadStudentsController@store')->middleware('cors');
Route::delete('acad-students/{id}', 'API\AcadStudentsController@destroy')->middleware('cors');
Route::get('acad-students/{id}','API\AcadStudentsController@show')->middleware('cors');
Route::patch('acad-students/{id}','API\AcadStudentsController@update')->middleware('cors');

Route::post('auth', 'API\AuthController@login')->middleware('cors'); 

Route::get('account','API\AccountsController@index')->middleware('cors');
Route::post('account','API\AccountsController@store')->middleware('cors');
Route::delete('account/{id}','API\AccountsController@destroy')->middleware('cors');
Route::patch('account/{id}', 'API\AccountsController@update')->middleware('cors');


Route::get('session','API\UserSessionController@index')->middleware('cors');





