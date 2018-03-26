<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 26/03/2018
 * Time: 1:03 AM
 */

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Teacher;
use App\Schoolyear;
use Illuminate\Support\Facades\DB;

class AreasController
{

    public function areasByGrade(request $request)
    {
        $pensum = DB::table('pensum')
            ->select('pensum.id as pensum_id', 'areas.id', 'areas.name')
            ->join('areas', 'areas.id', '=', 'pensum.areas_id')
            ->where('pensum.grade_id', '=', $request->grade_id)
            ->where('pensum.institution_id', '=', $request->institution_id)
            ->groupBy('areas.id')
            ->get();

        return $pensum;
    }

}