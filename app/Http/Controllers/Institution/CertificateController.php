<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use setasign\Fpdi\Fpdi;

use Auth;

use App\FinalReportAsignature;
use App\Helpers\Certificate;

use App\Pdf\NoteBook\Certificate as CertificatePdf;

class CertificateController extends Controller
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

    	return View('institution.partials.certificate.index')
    	->with('headquarters',$headquarters);
    }

    public function create(Request $request)
    {
    	
    	$path = 'pdf/'.time().'-'.$this->institution->id.'-certificados/';

        if(!file_exists($path))
        {   
            mkdir($path);
        }

    	$scale = $this->institution->scaleEvaluations()->with('wordExpresion')->get();
    	$averageAreas = FinalReportAsignature::getEnrollmentsAreasByGroup($request->group);

    	
    	foreach($request->enrollments as $enrollment)
    	{
    		$certificate = new Certificate($enrollment, $averageAreas, $scale, $request);
    		
    		$fileName = str_replace(' ', '', $certificate->enrollment->student->fullNameInverse);

    		$pdf = new CertificatePdf('p', 'mm', 'letter');
    		$pdf->institution = $this->institution;
    		$pdf->enrollment = $certificate->enrollment;
    		$pdf->create($certificate->create());
    		$pdf->Output($path.$fileName."Certificado.pdf", "F");
    		// exit();
    		// dd($certificate->create());
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

        $pdi->Output('D',$fileName.'.pdf');

    }
}
