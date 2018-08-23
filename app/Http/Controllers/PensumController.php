<?php

namespace App\Http\Controllers;

use App\GroupPensum;
use App\Pensum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PensumController extends Controller
{
    private $teacher = null;
    private $institution = null;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (Auth::guard('teachers')->check()) {
                $this->teacher = Auth::guard('teachers')->user()->teachers()->first();
                $this->institution = $this->teacher->institution;
            } elseif (Auth::guard('web_institution')->check()) {
                $this->institution = Auth::guard('web_institution')->user();
            }
            return $next($request);
        });
    }

    public function getAreasByGrade(Request $request){
        $pensum = DB::table('pensum')
            ->select('areas.id', 'areas.name')
            ->join('areas', 'areas.id', '=', 'pensum.areas_id')
            ->where('pensum.grade_id', '=', $request->grade_id)
            ->where('pensum.institution_id', '=', $this->institution->id)
            ->where('pensum.subjects_type_id', '=', 1)
            ->groupBy('areas.id')
            ->get();
        return $pensum;
    }

    public function getAreaByAsignatureOfPensum(Request $request){
        $area_selected = Pensum::getAreaByAsignatureOfPensum($request->grade_id, $request->asignature_id, $this->institution->id)[0];
        return $area_selected;
    }

    public function getAsignaturesByAreaPensum(Request $request){
        $asignatures = Pensum::getAsignaturesByAreaPensum($request->grade_id, $request->area_id, $this->institution->id);
        return $asignatures;
    }


}
