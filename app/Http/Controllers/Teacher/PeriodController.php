<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Workingday;
use App\Group;

class PeriodController extends ApiController
{
    public function byWorkingday(Workingday $workingday)
    {
    	$periods = $workingday->periods;

    	return $this->showAll($periods);
    }

    public function byGroup(Group $group)
    {
        $institution = $group->headquarter->institution;

    	$periods = $group->workingday->periods()
    	->with('period')
        ->where('institution_id', '=', $institution->id)
    	->get();

    	return $this->showAll($periods);
    }
}
