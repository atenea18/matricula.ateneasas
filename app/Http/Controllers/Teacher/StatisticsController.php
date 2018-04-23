<?php

namespace App\Http\Controllers\Teacher;

use App\Asignature;
use App\Enrollment;
use App\EvaluationPeriod;
use App\Grade;
use App\Group;
use App\Institution;
use App\MessagesExpressions;
use App\NoAttendance;
use App\Note;
use App\NotesFinal;
use App\NotesParametersPerformances;
use App\Performances;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;


class StatisticsController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teachers')->user()->teachers()->first();

        return View('teacher.partials.statistics.index');
    }

    public function getGroupsByGrade(Request $request)
    {

        $teacher = Auth::guard('teachers')->user()->teachers()->first();
        $institution_id = $teacher->institution_id;


        $groups = DB::table('group')
            ->join('headquarter', 'headquarter.id', '=', 'group.headquarter_id')
            ->select('group.id', 'group.name', 'headquarter.name as headquarter_name', 'group.grade_id', 'group.working_day_id')
            ->where('group.grade_id', '=', $request->grade_id)
            ->where('headquarter.institution_id', '=', $institution_id)
            ->get();


        return $groups;
    }

    public function  getAsignaturesGroupPensum(Request $request){
        $asignatures = DB::table('group_pensum')
            ->select(
                'asignatures.abbreviation', 'asignatures.name',
                'group_pensum.asignatures_id', 'group_pensum.ihs','group_pensum.order', 'group_pensum.percent')
            ->join('asignatures', 'asignatures.id','=','group_pensum.asignatures_id')
            ->where('group_pensum.group_id','=', $request->group_id)
            ->get();

        return $asignatures;
    }

    public function getConsolidated(Request $request)
    {
        $teacher = Auth::guard('teachers')->user()->teachers()->first();
        $enrollments = Group::enrollmentsByGroup($teacher->institution_id, $request->group_id);

        $notes_final = DB::table('notes_final')
            ->select('enrollment.id as enrollment_id', 'notes_final.value', 'notes_final.overcoming',
                'notes_final.id as notes_final_id', 'evaluation_periods.asignatures_id', 'evaluation_periods.periods_id',
                'evaluation_periods.id as evaluation_periods_id')
            ->join('evaluation_periods', 'evaluation_periods.id', '=', 'notes_final.evaluation_periods_id')
            ->join('enrollment', 'enrollment.id', '=', 'evaluation_periods.enrollment_id')
            ->join('grade', 'enrollment.grade_id', '=', 'grade.id')
            ->join('group_assignment', 'enrollment.id', '=', 'group_assignment.enrollment_id')
            ->join('group', 'group_assignment.group_id', '=', 'group.id')
            ->join('institution', 'enrollment.institution_id', '=', 'institution.id')
            ->join('headquarter', 'institution.id', '=', 'headquarter.institution_id')
            ->join('schoolyears', 'enrollment.school_year_id', 'schoolyears.id')
            ->whereColumn(
                [
                    ['headquarter.id', '=', 'group.headquarter_id'],
                    ['group.grade_id', '=', 'grade.id']
                ]
            )
            ->where('group.id', '=', $request->group_id)
            ->where('institution.id', '=', $teacher->institution_id)
            ->where('schoolyears.id', '=', '1')
            ->where('evaluation_periods.periods_id', '=', $request->periods_id)
            ->get();


        /*
        $no_attendance = DB::table('enrollment')
            ->select('enrollment.id as enrollment_id', 'evaluation_periods.id as evaluation_periods_id',
                'no_attendance.id as no_attendance_id', 'no_attendance.quantity'
            )
            ->join('grade', 'enrollment.grade_id', '=', 'grade.id')
            ->join('group_assignment', 'enrollment.id', '=', 'group_assignment.enrollment_id')
            ->join('group', 'group_assignment.group_id', '=', 'group.id')
            ->join('institution', 'enrollment.institution_id', '=', 'institution.id')
            ->join('headquarter', 'institution.id', '=', 'headquarter.institution_id')
            ->join('schoolyears', 'enrollment.school_year_id', 'schoolyears.id')
            ->leftJoin('evaluation_periods', 'evaluation_periods.enrollment_id', '=', 'enrollment.id')
            ->leftJoin('no_attendance', 'no_attendance.evaluation_periods_id', '=', 'evaluation_periods.id')
            ->whereColumn(
                [
                    ['headquarter.id', '=', 'group.headquarter_id'],
                    ['group.grade_id', '=', 'grade.id']
                ]
            )
            ->where('group.id', '=', $group_id)
            ->where('institution.id', '=', $teacher->institution_id)
            ->where('schoolyears.id', '=', '1')
            ->where('evaluation_periods.asignatures_id', '=', $asignatures_id)
            ->where('evaluation_periods.periods_id', '=', $period_id)
            ->get();
        */
        #representa una lista de estudiantes con sus notas existente
        $collection = [];

        foreach ($enrollments as $key => $enrollment) {

            $collection_notes_final = [];
            foreach ($notes_final as $keyNotes => $note) {
                if ($enrollment->id == $note->enrollment_id) {
                    array_push($collection_notes_final, $note);
                    unset($notes_final[$keyNotes]);
                }
            }
            /*
            /*
            if (count($no_attendance) >= 1) {
                foreach ($no_attendance as $keyNoAtt => $item) {
                    if ($enrollment->id == $item->enrollment_id) {
                        $enrollment->no_attendance = $item->quantity;
                        $enrollment->evaluation_periods_id = $item->evaluation_periods_id;
                        unset($no_attendance[$keyNoAtt]);
                    }
                }
            }
            if (count($no_attendance) < 1) {
                $enrollment->no_attendance = '';
                $enrollment->evaluation_periods_id = 0;
            }
            */

            $enrollment->notes_final = $collection_notes_final;
            array_push($collection, $enrollment);
        }


        return $collection;
    }
}
