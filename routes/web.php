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

use App\Institution;

Route::get('/', function () {

	//return view('home.index');
	return redirect()->route('institution.login');
    // return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// RUTAS AJAX
Route::group(['prefix'=>'ajax'], function(){

	Route::get('/group/getByWorkingday', 'GroupController@getByWorkingDay');
	Route::get('/student/getFamily/{id}', 'StudentController@getFamily')->name('student.getFamily');
	Route::get('/student/getFamilyById/{id}', 'StudentController@getFamilyById');
	Route::get('/family/search', 'FamilyController@search')->name('family.search');
	
});

Route::group(['middleware'=>'admin_guest'], function(){
	Route::post('admin_logout', 'AdministratorAuth\LoginController@logout');
	Route::get('admin_login', 'AdministratorAuth\LoginController@showLoginForm');
	Route::post('admin_login', 'AdministratorAuth\LoginController@login');
});

Route::group(['prefix'=>'admin','middleware'=>'admin_auth'], function(){

	Route::get('/', function(){
		return View('admin.partials.import.home');
	})->name('admin.home');

	Route::post('/logout', 'AdministratorAuth\LoginController@logout');
	Route::resource('institution', 'InstitutionController');
	Route::put('institution/{id}/changePassword', 'InstitutionController@changePassword')->name('institution.changePassword');


	// Excel's
	Route::get('excel', 'ExcelController@exportInstitutions')->name('institutions.excel');
	Route::get('import/students', function(){
		
		$institutions = Institution::orderBy('name', 'DESC')->pluck('name', 'id');
		
		return View('admin.partials.import.student.old_students')
		->with('institutions',$institutions);
	})->name('import.old_students.form');
});

//Logged in users/seller cannot access or send requests these pages
Route::group(['middleware' => 'institution_guest'], function() {

	// Route::get('institution_register', 'InstitutionAuth\RegisterController@showRegistrationForm');
	// Route::post('institution_register', 'InstitutionAuth\RegisterController@register');

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
	})->name('institution.home');

	Route::get('/home', function(){
		return view('institution.dashboard.index');
	});

	// Rutas para Matricula
	Route::resource('enrollment', 'EnrollmentController', ['only'=> ['store', 'edit','update', 'destroy']]);
	Route::get('enrollment/create/{id}', 'EnrollmentController@createById')->name('enrollment.create');
	Route::get('enrollment/', 'Institution\EnrollmentController@index')->name('institution.enrollment.show');
	Route::get('enrollment-card/grade', 'EnrollmentController@cardGrade')->name('enrollment.card.grade');
    Route::get('enrollment-card/group', 'EnrollmentController@cardGroup')->name('enrollment.card.group');
    Route::get('enrollment-card/student', 'EnrollmentController@cardStudent')->name('enrollment.card.student');
    Route::post('enrollment-card/generate', 'EnrollmentController@generateCard')->name('enrollment.card.generate');
    Route::post("enrollment/autocomplete", array('as'=>'enrollment.autocomplete','uses'=>
     'EnrollmentController@enrollmentAutocomplete'));

	// Ruta para estudiante
	Route::resource('student', 'StudentController');
	Route::post('student/addFamily', 'StudentController@addFamily')->name('student.addFamily');
	Route::post('student/attachFamily', 'StudentController@attachFamily')->name('student.attachFamily');
	Route::post('student/dettachFamily', 'StudentController@dettachFamily')->name('student.dettachFamily');
	Route::put('student/updateFamily/{id}', 'StudentController@updateFamily')->name('student.updateFamily');
	Route::delete('student/deleteFamily/{id}', 'StudentController@deleteFamily')->name('student.deleteFamily');
	Route::get('student/searchFamily', 'StudentController@searchFamily')->name('student.searchFamily');

	// Ruta para los salones de clase
	Route::resource('group', 'GroupController');

	// Ruta para las sedes
	Route::resource('headquarter', 'HeadquarterController');	

	// Planillas
	Route::get('sheet', 'SheetController@index')->name('sheet');

	// PDF's
	Route::get('pdf/studentAttendance/{group_id}/{year}', 'PdfController@attendance')->name('student.attendance.pdf');
	Route::post('pdf/studentAttendance', 'PdfController@attendances')->name('student.attendances.pdf');

});

Route::group(['prefix' => 'excel'], function() {
   
   	Route::post('upload/identification', 'ExcelController@importIdentification')->name('import.identification');
   	Route::post('upload/address', 'ExcelController@importAddress')->name('import.address');
   	Route::post('upload/students', 'ExcelController@importStudents')->name('import.students');
	Route::post('upload/institutions', 'ExcelController@importInstitutions')->name('import.institutions');
   	Route::post('upload/headquarters', 'ExcelController@importHeadquarters')->name('import.headquarters');
   	Route::post('upload/groups', 'ExcelController@importGroups')->name('import.groups');
   	Route::post('upload/academicInformation', 'ExcelController@importAcademicInformation')->name('import.academicInformation');
   	Route::post('upload/medicalInformation', 'ExcelController@importMedicalInformation')->name('import.medicalInformation');
   	Route::post('upload/displacements', 'ExcelController@importDisplacements')->name('import.displacements');
   	Route::post('upload/socioEconomic', 'ExcelController@importSocioEconomic')->name('import.socioEconomic');
   	Route::post('upload/teritorrialty', 'ExcelController@importTeritorrialty')->name('import.teritorrialty');
   	Route::post('upload/family', 'ExcelController@importFamily')->name('import.family');
   	Route::post('upload/familyRelation', 'ExcelController@importFamilyRelation')->name('import.familyRelation');
   	Route::post('upload/enrollment', 'ExcelController@importEnrollment')->name('import.enrollment');

   	Route::post('upload/old_students', 'ExcelController@oldStudent')->name('import.old_students');
});
