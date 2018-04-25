<?php

namespace App\Http\Controllers;

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

    public function getPeriodsByWorkingDay($working_day_id)
    {

        $periodsWorkingDay = DB::table('working_day_periods')
            ->select(
                'periods.name as periods_name', 'periods.id as periods_id',
                'working_day_periods.start_date', 'working_day_periods.end_date', 'working_day_periods.percent', 'working_day_periods.id as working_day_periods_id',
                'periods_state.name as periods_state_name', 'periods_state.id as periods_state_id'
            )
            ->join('periods_state', 'periods_state.id', '=', 'working_day_periods.periods_state_id')
            ->join('periods', 'periods.id', '=', 'working_day_periods.periods_id')
            ->where('working_day_periods.working_day_id', '=', $working_day_id)
            ->where('working_day_periods.institution_id', '=', $this->institution->id)
            ->get();

        return $periodsWorkingDay;
    }

    public function getInstitutionOfTeacher(Request $request)
    {
        $institution = Institution::where('id', '=',$this->institution->id)->get();
        if ($request->ajax()) {
            return $institution[0];
        }
    }

    public function index()
    {
        return View('teacher.partials.statistics.index');
    }

    public function indexInstitution()
    {
        return View('institution.partials.statistics.index');
    }

    public function getGroupsByGrade(Request $request)
    {

        $groups = DB::table('group')
            ->join('headquarter', 'headquarter.id', '=', 'group.headquarter_id')
            ->select('group.id', 'group.name', 'headquarter.name as headquarter_name', 'group.grade_id', 'group.working_day_id')
            ->where('group.grade_id', '=', $request->grade_id)
            ->where('headquarter.institution_id', '=', $this->institution->id)
            ->get();


        return $groups;
    }

    public function getAsignaturesGroupPensum(Request $request)
    {

        $asignatures = DB::table('group_pensum')
            ->select(
                'asignatures.abbreviation', 'asignatures.name',
                'group_pensum.asignatures_id', 'group_pensum.ihs', 'group_pensum.order', 'group_pensum.percent')
            ->join('asignatures', 'asignatures.id', '=', 'group_pensum.asignatures_id')
            ->where('group_pensum.group_id', '=', $request->group_id)
            ->get();

        return $asignatures;
    }

    public function getConsolidated(Request $request)
    {
        $enrollments = Group::enrollmentsByGroup($this->institution->id, $request->group_id);

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
            ->where('institution.id', '=', $this->institution->id)
            ->where('schoolyears.id', '=', '1')
            ->where('evaluation_periods.periods_id', '=', $request->periods_id)
            ->get();


        $collection = [];

        foreach ($enrollments as $key => $enrollment) {

            $collection_notes_final = [];
            foreach ($notes_final as $keyNotes => $note) {
                if ($enrollment->id == $note->enrollment_id) {
                    array_push($collection_notes_final, $note);
                    unset($notes_final[$keyNotes]);
                }
            }

            $enrollment->notes_final = $collection_notes_final;
            array_push($collection, $enrollment);
        }


        return $collection;
    }
}
