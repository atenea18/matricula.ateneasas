<?php

namespace App\Http\Controllers\Headquarter;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Headquarter;
use App\Workingday;
use App\Grade;

class GroupController extends ApiController
{
    public function getGroup(Headquarter $headquarter, Workingday $workingDay, Grade $grade)
    {

    	$groups = $headquarter->groups()
    				->where([
    					['working_day_id', '=', $workingDay->id],
    					['headquarter_id', '=', $headquarter->id],
    					['grade_id', '=', $grade->id]
    				])
    				->get();

    	return $this->showAll($groups);
    }

    public function byHeadquarter(Headquarter $headquarter)
    {
        $groups = $headquarter->groups()->get();

        return $this->showAll($groups);
    }

    public function byGrade(Headquarter $headquarter, Grade $grade)
    {
        $groups = $headquarter->groups()
        ->where([
            ['headquarter_id', '=', $headquarter->id],
            ['grade_id', '=', $grade->id]
        ])
        ->get();

        return $this->showAll($groups);
    }

}
