<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\Notebook;

use App\Pdf\Notebook\Notebook as NotebookPDF;

use Auth;

use App\Institution;
use App\Enrollment;
use App\NotesFinal;

class NotebookController extends Controller
{
    private $institution = null;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (Auth::guard('web_institution')->check()) {

                $this->institution = Auth::guard('web_institution')->user();

            }

            return $next($request);

        });
    }

    public function index()
    {
    	$institution = Auth::guard('web_institution')->user();

    	$headquarters = $institution->headquarters()->get()->pluck('name', 'id');

    	return View('institution.partials.notebook.index')
    	->with('headquarters',$headquarters);
    }

    public function create(Request $request)
    {
        // $finalNote = \App\EvaluationPeriod::with('noteFinal')
        // ->with('noAttendances')
        // ->where([
        //     'enrollment_id'     =>  8527, 
        //     'periods_id'        =>  1, 
        //     'asignatures_id'    =>  4
        // ])
        // ->first();

        // dd($finalNote->noteFinal->value);
        
    	$scale = $this->institution->scaleEvaluations()
        ->with('wordExpresion')
        ->get();

    	foreach($request->enrollments as $key => $enrollment)
    	{

            $notebook = new Notebook($request, $this->institution);
            $notebook->setScaleEvaluation($scale);
            $data = $notebook->create(Enrollment::findOrFail($enrollment));

            $pdf = new NotebookPDF('p', 'mm', 'letter');
            $pdf->setData($data);
            $pdf->create();
            $pdf->Output();
            // dd ($data);

    	}

    }
}
