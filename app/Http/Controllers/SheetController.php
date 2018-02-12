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
    	$headquarters = Auth::guard('web_institution')
    					->user()
    					->headquarters
    					->pluck('name', 'id');

    	return View('institution.partials.sheet.index')
    			->with('institution',$institution)
    			->with('headquarters',$headquarters)
    			->with('journeys',$journeys)
    			->with('grades',$grades);
    }
}
