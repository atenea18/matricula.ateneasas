<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Institution;

class GradeController extends ApiController
{
    public function index(Institution $institution)
    {
    	$groups = $institution->headquarters()
    	->with('groups.grade')
    	->get()
    	->pluck('groups')
    	->collapse()
    	->pluck('grade')
    	->sortBy('id')
    	->unique()
    	->values();

    	return $this->showAll($groups);
    }
}
