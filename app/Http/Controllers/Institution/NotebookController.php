<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Filesystem\Filesystem;

use setasign\Fpdi\Fpdi;

use App\Helpers\Notebook;

use App\Pdf\Merge\Merge;
use App\Pdf\Notebook\Notebook as NotebookPDF;
use App\Pdf\Notebook\GeneralReport;


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
        
        // 
        $path = 'pdf/'.time().'-'.$this->institution->id.'-boletin/';

        if(!file_exists($path))
        {   
            mkdir($path);
        }


    	$scale = $this->institution->scaleEvaluations()
        ->with('wordExpresion')
        ->get();

        $eval_parameter = $this->institution->evaluationParameters()->where('school_year_id', '=', 1)->get();

    	foreach($request->enrollments as $key => $enrollment)
    	{
            $notebook = new Notebook($request, $this->institution);
            $notebook->setScaleEvaluation($scale);
            $notebook->setEvaluationParameters($eval_parameter);
            $data = $notebook->create(Enrollment::findOrFail($enrollment));



            //return response()->json($data);


            $fileName = str_replace(' ', '', $data['student']->fullNameInverse);

            if($data['config']['generalReportPeriod'] && !is_null($data['general_report']))
            {
                $report = new GeneralReport('p', 'mm', 'letter');
                $report->setData($data);
                $report->create();
                $report->Output($path.$fileName."ReporteGeneralPeriodo.pdf", "F");
            }

            $pdf = new NotebookPDF('p', 'mm', 'letter');
            $pdf->setData($data);
            $pdf->create();
            $pdf->Output($path.$fileName."boletin.pdf", "F");
    	}

        $this->merge($path, $this->institution->id.'boletines'.time(), 'p');
    }

    private function merge($path, $fileName = 'merge' ,$orientation='p')
    {
        $pdi = new Fpdi();

        $dir = opendir($path);
        $files = array();
        while ($archivo = readdir($dir)) {
                
            if (!is_dir($archivo)){
                // echo $archivo."<br />";
                array_push($files, $archivo);
            }
        }

        asort($files);
        
        foreach ($files as $file) 
        { 
            $pageCount = $pdi->setSourceFile($path.'/'.$file); 

            for ($i=1; $i <= $pageCount; $i++) { 
                
                $tpl = $pdi->importPage($i);
                $pdi->addPage($orientation); 

                $pdi->useTemplate($tpl); 
            }
        }
        $pdi->Output('I',$fileName.'.pdf');

    }
}
