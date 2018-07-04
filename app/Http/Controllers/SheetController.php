<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Institution;
use App\Workingday;
use App\Grade;

use Auth;
class SheetController extends Controller
{
    public function index()
    {
    	$institution = Auth::guard('web_institution')->user();

    	$journeys = Workingday::orderBy('id', 'ASC')->pluck('name', 'id');
    	$grades = Grade::orderBy('id', 'ASC')->pluck('name', 'id');
    	$headquarters = $institution
    					->headquarters
    					->pluck('name', 'id');
        $periods = $institution->periods()
        ->with('period')
        ->get()
        ->pluck('period')
        ->unique()
        ->values()
        ->pluck('name','id');

    	return View('institution.partials.sheet.index')
    			->with('institution',$institution)
                ->with('periods', $periods)
    			->with('headquarters',$headquarters)
    			->with('journeys',$journeys)
    			->with('grades',$grades);
    }

    public function evaluationPdf(Request $request)
    {
        dd($request->all());
    }
}
