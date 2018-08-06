<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Teacher;
use App\Schoolyear;
class PensumController extends ApiController
{
    public function index() 
    {

    }

    public function pensums(Teacher $teacher, $year)
    {	
    	$schoolYear = Schoolyear::where('year','=',$year)->first();

    	$pensums = $teacher->groups()
    	->with('pensums')
    	->where('schoolyear_id','=', $schoolYear->id)
    	->get()
    	->sortBy('grade_id')
    	->sortBy('name');

    	return $this->showAll($pensums);
    }
}
