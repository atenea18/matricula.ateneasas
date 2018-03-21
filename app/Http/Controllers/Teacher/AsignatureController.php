<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Teacher;
use App\Schoolyear;

class AsignatureController extends ApiController
{
    public function index(Teacher $teacher, $year)
    {

		$asignatures;

		if( $year == null || !isset($year) )    	
		{
    		$asignatures = $teacher->pensumAsignatures()
    		->with('asignature')
    		->with('area')
    		->with('group')
    		->with('subjectType')
    		->with('schoolYear')
    		->get();
		}
		else
		{
			$schoolYear = Schoolyear::where('year','=',$year)->first();
			$asignatures = $teacher->pensums()
	    	->where('schoolyear_id','=', $schoolYear->id)
	    	->with('asignature')
	    	->with('area')
	    	->with('group')
	    	->with('subjectType')
	    	->with('schoolYear')
	    	->get();
		}

    	return $this->showAll($asignatures);
    }
}
