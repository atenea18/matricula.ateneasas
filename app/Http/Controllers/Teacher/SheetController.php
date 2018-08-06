<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\EvaluationSheetHelper;
use App\Pdf\Sheet\EvaluationSheet;

use setasign\Fpdi\Fpdi;

use Auth;

use App\Workingday;
use App\Grade;
use App\GroupPensum;

class SheetController extends Controller
{
    public function index()
    {
    	$teacher = Auth::guard('teachers')->user()->teachers()->first();
    	$institution = $teacher->institution;

    	$pensums = $teacher->pensums()
            ->where('schoolyear_id', '=', 1)
            ->with('asignature')
            ->with('group')
            ->get()
            ->sortBy('asignature.name')
            ->toArray();

        $sub_pensums = $teacher->sub_pensums()
            ->where('schoolyear_id', '=', 1)
            ->with('asignature')
            ->with('subgroup')
            ->get()->toArray();

        $periods = $institution->periods()
        ->with('period')
        ->get()
        ->pluck('period')
        ->unique()
        ->values()
        ->pluck('name','id');

    	return View('teacher.partials.sheet.index')
		->with('institution',$institution)
        ->with('periods', $periods)
        ->with('pensums', $pensums)
        ->with('sub_pensums', $sub_pensums);
    }

    public function evaluationSheetByPensum(Request $request)
    {
    	$teacher = Auth::guard('teachers')->user()->teachers()->first();
    	$institution = $teacher->institution;
    	$parameters = $institution->evaluationParameters()
        ->with('criterias')->with('schoolYear')->where([
            ['school_year_id', '=', '1'],['group_type', '=', $request->group_type]
        ])->get();
        $periods = $institution->periods()
        ->with('period')->get()->pluck('period')->unique()->values()->pluck('name','id');

        $path = "./pdf/".time()."-".$request->institution_id.$request->year."-evaluationSheet/";
		
		if(!file_exists($path))
        {   
            mkdir($path);
        }

    	foreach($request->pensums as $key => $p)
    	{
    		$pensum = GroupPensum::with('teacher')
    		->with('asignature')
    		->findOrFail($p);
    		
    		$helper = new EvaluationSheetHelper($pensum->group_id, $request, $periods);

    		$response = $helper->getByAsignature($pensum->asignatures_id);

    		$evaluationSheet = new EvaluationSheet($request->orientation, 'mm', $request->papper);
            $evaluationSheet->institution = $institution;
            $evaluationSheet->group = $response['group'];
            $evaluationSheet->parameters = $parameters;
            $evaluationSheet->asignatureName = $pensum->asignature->name;
            $evaluationSheet->pensum = $response['pensums'][0];
            $evaluationSheet->periods = $periods;
            $evaluationSheet->create();
            $evaluationSheet->Output($path.$p.$key.".pdf", "F");
    	}

    	$this->merge($path, 'Planilla-Evaluacion'.time()."_".$request->institution_id, $request->orientation);  
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

        //ob_clean();
        $pdi->Output('D',$fileName.'.pdf');

        system('rm -rf ' . escapeshellarg($path), $retval);
    }
}
