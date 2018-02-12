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

Route::resource('enrollment', 'Institution\EnrollmentController', ['only'=>['index','show']]);

Route::get('Institution/{institution}/enrollments/{year}', 'Institution\EnrollmentController@enrollments')->name('institution.enrollments');
Route::get('headquarter/{headquarter}/{workingDay}/{grade}/getGroup', 'Headquarter\GroupController@getGroup')->name('api.headquarter.getGroup');