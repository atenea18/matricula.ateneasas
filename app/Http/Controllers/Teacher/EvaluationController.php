<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

class EvaluationController extends Controller
{
    public function index()
    {
    	$teacher = Auth::guard('teachers')->user()->teachers()->first();

    	// dd($teacher);
    	$pemsun = $teacher->pensums()
    	->where('schoolyear_id','=', 1)
    	->with('asignature')
    	->with('area')
    	->with('group')
    	->with('subjectType')
    	->with('schoolYear')
    	->get();


    	// dd($pemsun);
    	return View('teacher.partials.evaluation.index')
    	->with('teacher',$teacher)
    	->with('pemsun',$pemsun);
    }
}
