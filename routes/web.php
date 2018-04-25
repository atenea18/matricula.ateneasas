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

// Route::get('/home', 'HomeController@index')->name('home');


// RUTAS AJAX
Route::group(['prefix'=>'ajax'], function(){

	Route::get('/group/getByWorkingday', 'GroupController@getByWorkingDay');
	Route::get('/student/getFamily/{id}', 'StudentController@getFamily')->name('student.getFamily');
	Route::get('/student/getFamilyById/{id}', 'StudentController@getFamilyById');
	Route::get('/family/search', 'FamilyController@search')->name('family.search');

    Route::get('/allgrades', 'GradeController@getAllGrades');
    Route::get('/asignaturesByGrade', 'Teacher\AsignatureController@asignaturesByGrade');
    Route::get('/areasByGrade', 'Teacher\AreasController@areasByGrade');
    Route::get('/getInstitutionOfTeacher', 'Teacher\EvaluationController@getInstitutionOfTeacher');

    Route::get('/getSubgroupsByGrade', 'AcademicAssignmentController@getSubgroupsByGrade');
    Route::get('/getGroupsByGrade', 'StatisticsController@getGroupsByGrade');
    Route::get('/getConsolidated', 'StatisticsController@getConsolidated');
    Route::get('/getAsignaturesGroupPensum', 'StatisticsController@getAsignaturesGroupPensum');
    Route::get('/getPeriodsByWorkingDay/{working_day_id}', 'StatisticsController@getPeriodsByWorkingDay');

    Route::get('/printConsolidated', 'StatisticsController@printConsolidated');


});

Route::group(['middleware'=>'admin_guest'], function(){
	Route::post('admin_logout', 'AdministratorAuth\LoginController@logout');
	Route::get('admin_login', 'AdministratorAuth\LoginController@showLoginForm');
	Route::post('admin_login', 'AdministratorAuth\LoginController@login');
});

//Logged in users/seller cannot access or send requests these pages
Route::group(['middleware' => 'institution_guest'], function() {

	Route::post('institution_logout', 'InstitutionAuth\LoginController@logout');
	Route::get('institution_login', 'InstitutionAuth\LoginController@showLoginForm')->name('institution.login');
	Route::post('institution_login', 'InstitutionAuth\LoginController@login');

	//Password reset routes
	Route::get('institution_password/reset', 'InstitutionAuth\ForgotPasswordController@showLinkRequestForm');
	Route::post('institution_password/email', 'InstitutionAuth\ForgotPasswordController@sendResetLinkEmail');
	Route::get('institution_password/reset/{token}', 'InstitutionAuth\ResetPasswordController@showResetForm');
	Route::post('institution_password/reset', 'InstitutionAuth\ResetPasswordController@reset');

});


Route::group(['middleware'=>'teacher_guest'], function(){
	Route::post('teacher_logout', 'TeacherAuth\LoginController@logout');
	Route::get('teacher_login', 'TeacherAuth\LoginController@showLoginForm')->name('teacher.login');
	Route::post('teacher_login', 'TeacherAuth\LoginController@login');
});


Route::group(['prefix'=>'admin','middleware'=>'admin_auth'], function(){

	Route::get('/', function(){
		return View('admin.partials.home');
	})->name('admin.home');

	Route::post('/logout', 'AdministratorAuth\LoginController@logout');

	// Ruta para la institucion
	Route::resource('institution', 'InstitutionController');
	Route::put('institution/{id}/changePassword', 'InstitutionController@changePassword')->name('institution.changePassword');

	// Ruta Para las Areas
	Route::resource('area', 'Administrator\AreaController',['only'=>['list','create','store','edit','update','destroy']]);

	// Excel's
	Route::get('excel', 'ExcelController@exportInstitutions')->name('institutions.excel');
	Route::get('import/students', function(){
		
		$institutions = Institution::orderBy('name', 'DESC')->pluck('name', 'id');
		
		return View('admin.partials.import.student.old_students')
		->with('institutions',$institutions);
	})->name('import.old_students.form');

	Route::get('import/teachers', function(){
		
		$institutions = Institution::orderBy('name', 'DESC')->pluck('name', 'id');
		
		return View('admin.partials.import.teacher.old_teachers')
		->with('institutions',$institutions);
	})->name('import.old_teachers.form');
});


Route::group(['prefix'=>'institution', 'middleware' => 'institution_auth'], function(){
	
	Route::post('/logout', 'InstitutionAuth\LoginController@logout');
	
	Route::get('/', function(){
		return view('institution.partials.home.home');
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
	Route::post('student/uploadPicture', 'StudentController@uploadPicture')->name('student.uploadPicture');

	// Ruta para entes administrativos
	Route::resource('manager', 'Institution\ManagerController', ['only'=> ['create','store', 'edit','update', 'destroy']]);

	// Ruta para los docentes
	Route::resource('teacher', 'Institution\TeacherController', ['only'=> ['create','store', 'edit','update', 'destroy']]);
	Route::get('teacher/list', 'Institution\TeacherController@listTeacher')->name('institution.list.teacher');

	// Ruta para los salones de clase
	Route::resource('group', 'GroupController');
    Route::get('group-assignment', 'GroupController@assigment')->name('group.assignment');
    Route::resource('subgroup', 'Institution\SubgroupController');
    Route::get('subgroup/{subgroup}/assignment', 'Institution\SubgroupController@assigment')->name('subgroup.assignment');
    Route::post('subgroup/addEnrollment', 'Institution\SubgroupController@addEnrollment')->name('subgroup.addEnrollment');
    Route::post('subgroup/deleteEnrollment', 'Institution\SubgroupController@deleteEnrollment')->name('subgroup.deleteEnrollment');


    //Rutas para request Ajax
    Route::get('enrollmentByGroup/{group_id}', 'GroupController@getEnrollmentsByGroup');
    Route::get('allgrades', 'GradeController@getAllGrades');
    Route::get('groupsByGrade/{grade_id}', 'GroupController@GroupsByGrade');
    Route::get('enrollmentsWithOutGroup/{grade_id}', 'GroupController@getEnrollmentsWithOutGroup');

    Route::get('generateEvaluation', 'GenerateEvaluationController@index');


    Route::post('groupupdate', 'GroupController@groupUpdate');
    Route::post('groupinsert', 'GroupController@groupInsert');



    Route::get('getAreas', 'AreasAndAsignatureController@getAreas');
    Route::get('getAsignatures', 'AreasAndAsignatureController@getAsignatures');
    Route::get('getSubjectsType', 'AreasAndAsignatureController@getSubjectsType');
    Route::get('getTeachers', 'AreasAndAsignatureController@getTeachers');


    Route::get('getPensumByGrade/{grade_id}', 'AreasAndAsignatureController@getAreasByGrade');
    Route::get('getPensumByGroup/{group_id}', 'AreasAndAsignatureController@getAreasByGroup');




    Route::get('getAsignaturesPensumByGrade/{grade_id}/{area_id}', 'AreasAndAsignatureController@getAsignaturesPensumByGrade');
    Route::get('getAsignaturesPensumByGroup/{group_id}/{area_id}', 'AreasAndAsignatureController@getAsignaturesPensumByGroup');


    Route::post('storePensum', 'AreasAndAsignatureController@storePensum');
    Route::post('storePensumByGroup', 'AreasAndAsignatureController@storePensumByGroup');


    Route::post('deleteAreaPensumByGrade', 'AreasAndAsignatureController@deleteAreaPensumByGrade');
    Route::post('deleteAreaPensumByGroup', 'AreasAndAsignatureController@deleteAreaPensumByGroup');


    Route::post('deleteAsignaturePensumByGrade', 'AreasAndAsignatureController@deleteAsignaturePensumByGrade');
    Route::post('deleteAsignaturePensumByGroup', 'AreasAndAsignatureController@deleteAsignaturePensumByGroup');
    Route::post('editPensumGroup', 'AreasAndAsignatureController@editPensumGroup');



    Route::post('copyPensumByGrade', 'AreasAndAsignatureController@copyPensumByGrade');

    Route::get('statistics', 'StatisticsController@indexInstitution')->name('institution.statistics');


    //Asignación Académica
    Route::get('areas-and-asignature', 'AreasAndAsignatureController@index')->name('areaasignature.index');
    Route::get('assignment-subgroup', 'AcademicAssignmentController@viewSubgroup')->name('assignment.subgroup.index');
    Route::post('storeSubGroupPensumByGroup', 'AcademicAssignmentController@storeSubGroupPensumByGroup');
    Route::get('getSubgroupPensum', 'AcademicAssignmentController@getSubgroupPensum');


	// Ruta para las sedes
	Route::resource('headquarter', 'HeadquarterController');	

	// Planillas
	Route::get('sheet', 'SheetController@index')->name('sheet');

	// Constancias
	Route::resource('constancy', 'Institution\ConstancyController');

	// Parametros de Evaluación
	Route::resource('evaluationParameter', 'Institution\EvaluationParameterController');

	// Criterios de Evaluación
	Route::resource('criteria', 'Institution\CriteriaController', ['only'=>['store','create','edit','update','destroy']]);

	// Escala Valorativa 
	Route::resource('scaleEvaluation', 'Institution\ScaleEvaluationController');

	// Periodos
	Route::resource('period', 'Institution\PeriodController');
	Route::post('period/changeState', 'Institution\PeriodController@changeState')->name('period.changeState');

	// Boletines
	Route::get('notebook/index', 'Institution\NotebookController@index')->name('notebook.index');
	Route::post('notebook/create', 'Institution\NotebookController@create')->name('notebook.create');

	// PDF's
		// LISTAS DE ASISTENCIAS
	Route::get('pdf/studentAttendance/{group_id}/{year}', 'PdfController@attendance')->name('student.attendance.pdf');
	Route::post('pdf/studentAttendance', 'PdfController@attendances')->name('student.attendances.pdf');
		// PLANILLAS AUXILIARES DE EVALUACIÓN
	Route::post('pdf/evaluationSheet', 'PdfController@evaluationPdf')->name('evaluationSheet.pdf');
		// CONSTANCIA DE ESTUDIO
	Route::post('pdf/constancy_study', 'PdfController@constancyStudy')->name('constancy.study.pdf');

});

Route::group(['prefix'=>'teacher','middleware'=>'teacher_auth'], function(){

	Route::get('/', function(){
		return View('teacher.partials.home');
	})->name('teacher.home');

	Route::post('/logout', 'TeacherAuth\LoginController@logout');

	Route::get('evaluation', 'Teacher\EvaluationController@index')->name('teacher.evaluation');
    Route::get('statistics', 'StatisticsController@index')->name('teacher.statistics');

    Route::get('evaluation/periods/{group_id}/{type}/{asignatures_id}', 'Teacher\EvaluationController@evaluationPeriods')->name('teacher.evaluation.periods');

    Route::get('evaluation/evaluationParameter', 'Teacher\EvaluationController@evaluationParameter');

    Route::get('evaluation/getAsignatureById/{asignature_id}/{grade_id}', 'Teacher\EvaluationController@getAsignatureById');
    Route::get('evaluation/getGradeById/{grade_id}', 'Teacher\EvaluationController@getGradeById');
    Route::get('evaluation/getPeriodsByWorkingDay/{working_day_id}', 'Teacher\EvaluationController@getPeriodsByWorkingDay');
    Route::get('evaluation/getCollectionsNotes/{group_id}/{asignature_id}/{period_id}', 'Teacher\EvaluationController@getCollectionsNotes');
    Route::get('evaluation/searchPerformances', 'Teacher\EvaluationController@searchPerformances');
    Route::get('evaluation/getGroupPensum', 'Teacher\EvaluationController@getGroupPensum');
    Route::get('evaluation/getRelationPerformances', 'Teacher\EvaluationController@getRelationPerformances');

    Route::post('evaluation/storeEvaluationPeriods', 'Teacher\EvaluationController@storeEvaluationPeriods');
    Route::post('evaluation/storeFinalNotes', 'Teacher\EvaluationController@storeFinalNotes');
    Route::post('evaluation/storeNotes', 'Teacher\EvaluationController@storeNotes');
    Route::post('evaluation/storePerformances', 'Teacher\EvaluationController@storePerformances');
    Route::post('evaluation/storeRelationPerformances', 'Teacher\EvaluationController@storeRelationPerformances');
    Route::post('evaluation/storeNoAttendance', 'Teacher\EvaluationController@storeNoAttendance');
    Route::post('evaluation/deleteRelationPerformances', 'Teacher\EvaluationController@deleteRelationPerformances');


	// Rutas para el Informe General de Periodo
	Route::resource('generalReport', 'Teacher\GeneralReportController', ['except'=>'show']);
	
	// Rutas para las Observaciones Generales
	Route::resource('generalObservation', 'Teacher\GeneralObservationController', ['except'=>'show']);

	// Ruta para la recuperacion de asignaturas
	Route::get('recovery/{group}/{asignature}/group', 'Teacher\RecoveryController@byGroup')->name('group.recovery');
	Route::resource('recovery', 'Teacher\RecoveryController', ['only'=>['index', 'update','store']]);

	// Ruta para la evaluación de periodo pendiente
	Route::get('periodPending/{group}/{asignature}/group', 'Teacher\PeriodPendingController@byGroup')->name('group.pendingPeriod');

	// Ruta para la configuración
	Route::get('setting', 'Teacher\SettingController@index')->name('teacher.setting');
	Route::get('setting/security', 'Teacher\SettingController@security')->name('teacher.setting.security');
	Route::put('setting/{manager}/updateAccount', 'Teacher\SettingController@updateAccount')->name('setting.updateAccount');
	Route::put('setting/{manager}/updatePassword', 'Teacher\SettingController@updatePassword')->name('setting.updatePassword');

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
   	Route::post('upload/old_teachers', 'ExcelController@oldTeacher')->name('import.old_teachers');
});
