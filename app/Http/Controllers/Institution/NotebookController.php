<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\Notebook;

use Auth;

use App\Institution;
use App\Enrollment;

class NotebookController extends Controller
{
    public function index()
    {
    	$institution = Auth::guard('web_institution')->user();

    	$headquarters = $institution->headquarters()->get()->pluck('name', 'id');

    	return View('institution.partials.notebook.index')
    	->with('headquarters',$headquarters);
    }

    public function create(Request $request)
    {

    	// dd($request->all());

    	foreach($request->enrollments as $key => $enrollment)
    	{

    		$notebook = new Notebook($request);
    		$enrollment_obj = Enrollment::where('id', '=', $enrollment)
    		->with('student.identification.identification_type')
    		->with('student.address')
    		->with('group.headquarter.institution')
    		->with([
    			'evaluationPeriod.noteFinal',
    			'evaluationPeriod.asignature',
    			// 'evaluationPeriod.notes.noteParameter.notePerformances.performance.message'
    		])
    		->with(['evaluationPeriod.notes.noteParameter.notePerformances' => function($q){
    				$q->with(['groupPensum' => function($q1){
    						$q1->with('group')
    						// ->where('group_id', '=', 877)
    						->get();
    				}])
    				->with('performance.message')
    				->where('group_pensum_id', '=', 1278)
    				->get();
    			}])
    		->first()
    		->evaluationPeriod->pluck('notes');

    		return ($enrollment_obj);

    		dd( $notebook->create($enrollment) );
    	
    	}

    }
}
