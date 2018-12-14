<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
    	// dd($request->all());
    	$scale = $this->institution->scaleEvaluations()->with('wordExpresion')->get();
    	$averageAreas = FinalReportAsignature::getEnrollmentsAreasByGroup($request->group);

    	
    	foreach($request->enrollments as $enrollment)
    	{
    		$certificate = new Certificate($enrollment, $averageAreas, $scale, $request);
    		// dd($certificate->create());

    		$pdf = new CertificatePdf('p', 'mm', 'letter');
    		$pdf->institution = $this->institution;
    		$pdf->enrollment = $certificate->enrollment;
    		$pdf->create($certificate->create());
    		$pdf->Output();
    		exit();
    		// dd($certificate->create());
    	}
    }
}
