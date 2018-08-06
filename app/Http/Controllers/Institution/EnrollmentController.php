<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use Auth;

use App\Institution;
use App\Schoolyear;

class EnrollmentController extends ApiController
{	
	public function index()
	{
		$institution_id = Auth::guard('web_institution')->user()->id;

		return view('institution.partials.enrollment.index')
				->with('institution_id',$institution_id);
	}

    public function enrollments(Institution $institution, $year)
    {	
    	$schoolYear = Schoolyear::where('year','=',$year)->first();

        $enrollments = $institution->enrollments()
        ->with('student')
        ->with('group.headquarter')
        ->with('schoolYear')
        ->where('school_year_id','=', $schoolYear->id)
        ->get();

        // dd($enrollments);
        return $this->showAll($enrollments);
    }
}
