<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Teacher;
use App\Schoolyear;
use Illuminate\Support\Facades\DB;


class AsignatureController extends ApiController
{
    public function index(Teacher $teacher, $year)
    {

        $asignatures;

        if ($year == null || !isset($year)) {
            $asignatures = $teacher->pensumAsignatures()
                ->with('asignature')
                ->with('area')
                ->with('group')
                ->with('subjectType')
                ->with('schoolYear')
                ->get();
        } else {
            $schoolYear = Schoolyear::where('year', '=', $year)->first();
            $asignatures = $teacher->pensums()
                ->where('schoolyear_id', '=', $schoolYear->id)
                ->with('asignature')
                ->with('area')
                ->with('group')
                ->with('subjectType')
                ->with('schoolYear')
                ->get();
        }

        return $this->showAll($asignatures);
    }

    public function asignaturesByGrade(request $request)
    {
        $subject_type_id =  $request->isGroup == "true"?1:2;

        $pensum = DB::table('pensum')
            ->select('pensum.id as pensum_id', 'asignatures.id', 'asignatures.name', 'pensum.areas_id')
            ->join('asignatures', 'asignatures.id', '=', 'pensum.asignatures_id')
            ->where('pensum.grade_id', '=', $request->grade_id)
            ->where('pensum.areas_id', '=', $request->areas_id)
            ->where('pensum.subjects_type_id', '=', $subject_type_id)
            ->where('pensum.institution_id', '=', $request->institution_id)
            ->get();

        return $pensum;
    }

}
