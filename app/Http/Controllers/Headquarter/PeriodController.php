<?php

namespace App\Http\Controllers\Headquarter;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Headquarter;

class PeriodController extends ApiController
{
    public function index(Headquarter $headquarter)
    {
    	$periods = $headquarter->institution->periods()
    	->with('period')
        ->with('schoolyear')
        ->with('state')
        ->with('workingday')
        ->get()
        ->pluck('period')
        ->unique()
        ->values();

        return $this->showAll($periods);
    }
}
