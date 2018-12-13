<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

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
}
