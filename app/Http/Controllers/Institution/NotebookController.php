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

    	// dd($request->all());

    	foreach($request->enrollments as $key => $enrollment)
    	{

            // dd($request->all());
            $notebook = new Notebook($request, $this->institution);
            dd($notebook->create(Enrollment::findOrFail($enrollment)));

    	
    	}

    }
}
