<?php

namespace App\Http\Controllers\Headquarter;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Headquarter;
use App\Workingday;
use App\Grade;

class SubgroupController extends ApiController
{
    public function getSubgroups(Headquarter $headquarter, Grade $grade)
    {

    	$groups = $headquarter->subgroups()
    				->where([
    					['headquarter_id', '=', $headquarter->id],
    					['grade_id', '=', $grade->id]
    				])
    				->orderBy('name', 'ASC')
                    ->get();


    	// dd($groups);
    	return $this->showAll($groups);
    }
}
