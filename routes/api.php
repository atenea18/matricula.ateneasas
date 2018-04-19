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



// RUTA PARA DOCENTES
Route::resource('teacher', 'Institution\TeacherController', ['only'=>['index','show']]);
Route::get('institution/{institution}/teachers/{year?}', 'Institution\TeacherController@getByInstitution')->name('institution.teachers');

// RUTA PARA LAS AREAS DESDE EL ADMINISTRADOR
Route::resource('area', 'Administrator\AreaController', ['only'=>['index','show']]);

// RUTA PARA LOS GRUPOS DE UNA  INSTITUCION
Route::get('institution/{institution}/groups', 'Institution\GroupController@index');
Route::get('headquarter/{headquarter}/{workingDay}/{grade}/getGroup', 'Headquarter\GroupController@getGroup')->name('api.headquarter.getGroup');
Route::get('headquarter/{headquarter}/groups', 'Headquarter\GroupController@byHeadquarter')->name('headquarter.groups');

// RUTA PARA LOS SUBGRUPOS DE UNA INSTITUCIÓN
Route::get('institution/{institution}/getSubgroups', 'Institution\SubgroupController@getSubgroups');
Route::get('headquarter/{headquarter}/{grade}/getSubgroups', 'Headquarter\SubgroupController@getSubgroups')->name('api.headquarter.getSubgroups');

// RUTA PARA LOS GRADOS DE UNA  INSTITUCION
Route::get('institution/{institution}/grades', 'Institution\GradeController@index');

// RUTA PARA LOS ESTUDIANTES DE UNA GRUPO
Route::get('group/{group}/students', 'Institution\GroupController@students');
Route::get('group/{group}/pensums', 'Institution\GroupController@pensums');
Route::get('group/{group}/enrollments', 'Institution\GroupController@enrollments');

// RUTA PARA LOS CRITERIOS DE EVALUACIÓN
Route::resource('criteria', 'Institution\CriteriaController',['only'=>['show']]);

// RUTA PARA LOS PENSUMS
Route::get('teacher/{teacher}/asignatures/{year}', 'Teacher\AsignatureController@index')->name('teacher.asignatures');

// RUTA PARA LOS PERIODOS
Route::get('periods/{institution}', 'Institution\PeriodController@all')->name('institution.periods.all');
Route::get('periodsByWorkingday/{institution}/{workingday_id}/{year?}', 'Institution\PeriodController@showByWorkingday')->name('institution.period.showByWorkingday');
Route::get('periodsByPeriod/{institution}/{period_id}/{year?}', 'Institution\PeriodController@showByPeriod')->name('institution.period.showByPeriod');
Route::get('periodByGroup/{group}', 'Teacher\PeriodController@byGroup');
Route::get('headquarter/{headquarter}/periods', 'Headquarter\PeriodController@index')->name('headquarter.periods');

// RUTA PARA LAS OBSERVACIONES GENERALES
Route::resource('generalObservation', 'Teacher\GeneralObservationController', ['only'=>['show']]);

// RUTA PARA EL INFORME GENERAL DE PERIODO
Route::resource('generalReport', 'Teacher\GeneralReportController', ['only'=>['show']]);