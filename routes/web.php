<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

	return view('home.index');
	// return redirect()->route('institution.login');
    // return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// RUTAS AJAX
Route::group(['prefix'=>'ajax'], function(){

	Route::get('/group/getByWorkingday', 'GroupController@getByWorkingDay');
	Route::get('/student/getFamily/{id}', 'StudentController@getFamily')->name('student.getFamily');
	Route::get('/student/getFamilyById/{id}', 'StudentController@getFamilyById');
	
});

//Logged in users/seller cannot access or send requests these pages
Route::group(['middleware' => 'institution_guest'], function() {

	Route::get('institution_register', 'InstitutionAuth\RegisterController@showRegistrationForm');
	Route::post('institution_register', 'InstitutionAuth\RegisterController@register');
	Route::post('institution_logout', 'InstitutionAuth\LoginController@logout');
	Route::get('institution_login', 'InstitutionAuth\LoginController@showLoginForm')->name('institution.login');
	Route::post('institution_login', 'InstitutionAuth\LoginController@login');

	//Password reset routes
	Route::get('institution_password/reset', 'InstitutionAuth\ForgotPasswordController@showLinkRequestForm');
	Route::post('institution_password/email', 'InstitutionAuth\ForgotPasswordController@sendResetLinkEmail');
	Route::get('institution_password/reset/{token}', 'InstitutionAuth\ResetPasswordController@showResetForm');
	Route::post('institution_password/reset', 'InstitutionAuth\ResetPasswordController@reset');

});

Route::group(['prefix'=>'institution', 'middleware' => 'institution_auth'], function(){
	
	Route::post('/logout', 'InstitutionAuth\LoginController@logout');
	
	Route::get('/', function(){
		return view('institution.dashboard.index');
	});

	Route::get('/home', function(){
		return view('institution.dashboard.index');
	});

	// Rutas para inscripcion
	Route::resource('enrollment', 'EnrollmentController');
	Route::get('enrollment/create/{id}', 'EnrollmentController@createById')->name('enrollment.create');
	Route::get('enrollment/lists/{state}', 'EnrollmentController@lists')->name('enrollment.lists');
	Route::get('enrollment-card/grade', 'EnrollmentController@cardGrade')->name('enrollment.card.grade');
    Route::get('enrollment-card/group', 'EnrollmentController@cardGroup')->name('enrollment.card.group');
    Route::get('enrollment-card/student', 'EnrollmentController@cardStudent')->name('enrollment.card.student');
    Route::post('enrollment-card/generate', 'EnrollmentController@generateCard')->name('enrollment.card.generate');
    Route::post("enrollment/autocomplete", array('as'=>'enrollment.autocomplete','uses'=> 'EnrollmentController@enrollmentAutocomplete'));

	// Ruta para estudiante
	Route::resource('student', 'StudentController');
	Route::post('student/addFamily', 'StudentController@addFamily')->name('student.addFamily');
	Route::put('student/updateFamily/{id}', 'StudentController@updateFamily')->name('student.updateFamily');
	Route::delete('student/deleteFamily/{id}', 'StudentController@deleteFamily')->name('student.deleteFamily');

	// Ruta para los salones de clase
	Route::resource('group', 'GroupController');

	// Ruta para las sedes
	Route::resource('headquarter', 'HeadquarterController');	

	// PDF's
	Route::get('pdf/inscription/{group_id}/{year}', 'PdfController@inscription')->name('inscription.group.pdf');
});