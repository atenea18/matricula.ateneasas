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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// RUTA PARA MATRICULAS
Route::resource('enrollment', 'Institution\EnrollmentController', ['only'=>['index','show']]);
Route::get('Institution/{institution}/enrollments/{year}', 'Institution\EnrollmentController@enrollments')->name('institution.enrollments');
Route::get('headquarter/{headquarter}/{workingDay}/{grade}/getGroup', 'Headquarter\GroupController@getGroup')->name('api.headquarter.getGroup');

// RUTA PARA DOCENTES
Route::resource('teacher', 'Institution\TeacherController', ['only'=>['index','show']]);
Route::get('institution/{institution}/teachers/{year?}', 'Institution\TeacherController@getByInstitution')->name('institution.teachers');

// RUTA PARA LAS AREAS DESDE EL ADMINISTRADOR
Route::resource('area', 'Administrator\AreaController', ['only'=>['index','show']]);

// RUTA PARA LOS GRUPOS DE UNA  INSTITUCION
Route::get('institution/{institution}/groups', 'Institution\GroupController@index');

// RUTA PARA LOS GRUPOS DE UNA  INSTITUCION
Route::get('institution/{institution}/grades', 'Institution\GradeController@index');

// RUTA PARA LOS ESTUDIANTES DE UNA GRUPO
Route::get('group/{group}/students', 'Institution\GroupController@students');