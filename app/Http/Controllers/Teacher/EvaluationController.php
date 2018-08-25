<?php

namespace App\Http\Controllers\Teacher;

use App\Asignature;
use App\Enrollment;
use App\EvaluationParameter;
use App\EvaluationPeriod;
use App\Grade;
use App\Group;
use App\Helpers\Evaluation\RelationshipPerformances\MainRelationship;
use App\Helpers\Evaluation\RelationshipPerformances\ParamsRelationship;
use App\Institution;
use App\MessagesExpressions;
use App\MessagesScale;
use App\NoAttendance;
use App\Note;
use App\NotesFinal;
use App\NotesParametersPerformances;
use App\Performances;
use App\Subgroup;
use App\NotesParametersPerformancesSub;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{


    public function getAsignatureById(Request $request)
    {
        if ($request->isGroup == "true") {
            $asignatures = Asignature::select('asignatures.id', 'asignatures.name', 'pensum.areas_id', 'pensum.grade_id', 'pensum.id as pensum_id',
                'asignatures.subjects_type_id')
                ->join('pensum', 'pensum.asignatures_id', '=', 'asignatures.id')
                ->where('pensum.grade_id', '=', $request->grade_id)
                ->where('asignatures.id', '=', $request->asignatureid)
                ->get();
            return $asignatures[0];
        } else {
            $asignatures = Asignature::select('asignatures.id', 'asignatures.name',
                'sub_group_pensum.areas_id as areas_id',
                'sub_group_pensum.id as pensum_id')
                ->join('sub_group_pensum', 'sub_group_pensum.asignatures_id', '=', 'asignatures.id')
                ->where('asignatures.id', '=', $request->asignatureid)
                ->get();
            return $asignatures[0];
        }

    }

    public function getSubAsignatureById($asignatures_id, $grade_id)
    {
        $asignatures = Asignature::select('asignatures.id', 'asignatures.name', 'sub_group_pensum.areas_id', 'sub_group_pensum.grade_id', 'sub_group_pensum.id as sub_group_pensum_id')
            ->join('sub_group_pensum', 'sub_group_pensum.asignatures_id', '=', 'asignatures.id')
            ->where('sub_group_pensum.grade_id', '=', $grade_id)
            ->where('asignatures.id', '=', $asignatures_id)
            ->get();
        return $asignatures[0];
    }

    public function getGradeById($grade_id)
    {
        $grade = Grade::where('id', '=', $grade_id)->get();
        return $grade[0];
    }


    public function getPeriodsByWorkingDay(Request $request)
    {

        $teacher = Auth::guard('teachers')->user()->teachers()->first();
        $institution_id = $teacher->institution_id;

        if ($request->isGroup == "true") {
            $periodsWorkingDay = DB::table('working_day_periods')
                ->select(
                    'periods.name as periods_name', 'periods.id as periods_id',
                    'working_day_periods.start_date', 'working_day_periods.end_date', 'working_day_periods.percent', 'working_day_periods.id as working_day_periods_id',
                    'periods_state.name as periods_state_name', 'periods_state.id as periods_state_id'
                )
                ->join('periods_state', 'periods_state.id', '=', 'working_day_periods.periods_state_id')
                ->join('periods', 'periods.id', '=', 'working_day_periods.periods_id')
                ->where('working_day_periods.working_day_id', '=', $request->workingdayid)
                ->where('working_day_periods.institution_id', '=', $institution_id)
                ->get();

            return $periodsWorkingDay;
        } else {
            $periodsWorkingDay = DB::table('section_periods')
                ->select(
                    'periods.name as periods_name', 'periods.id as periods_id',
                    'section_periods.start_date', 'section_periods.end_date', 'section_periods.percent', 'section_periods.id as working_day_periods_id',
                    'periods_state.name as periods_state_name', 'periods_state.id as periods_state_id'
                )
                ->join('periods_state', 'periods_state.id', '=', 'section_periods.periods_state_id')
                ->join('periods', 'periods.id', '=', 'section_periods.periods_id')
                ->where('section_periods.section_id', '=', $request->workingdayid)
                ->get();

            return $periodsWorkingDay;
        }

    }


    public function getGroupPensum(Request $request)
    {
        if ($request->isGroup == "true") {
            $pensum = DB::table('group_pensum')
                ->where('group_pensum.group_id', '=', $request->group_id)
                ->where('group_pensum.asignatures_id', '=', $request->asignatures_id)
                ->where('group_pensum.schoolyear_id', '=', $request->school_year_id)
                ->get();
        } else {
            $pensum = DB::table('sub_group_pensum')
                ->where('sub_group_pensum.sub_group_id', '=', $request->group_id)
                ->where('sub_group_pensum.asignatures_id', '=', $request->asignatures_id)
                ->where('sub_group_pensum.schoolyear_id', '=', $request->school_year_id)
                ->get();
        }
        return $pensum;
    }

}
