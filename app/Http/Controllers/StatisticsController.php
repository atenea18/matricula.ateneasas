<?php

namespace App\Http\Controllers;

use App\GroupPensum;
use Auth;
use App\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Utils\GenerateRating;
use App\Helpers\Statistics\MainConsolidated;
use App\Helpers\Statistics\ParamsStatistics;


class StatisticsController extends Controller
{
    private $teacher = null;
    private $institution = null;
    private $params = null;

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

    /**
     * Obtiene un array con la información para el consolidado solicitado
     *
     */
    public function getConsolidated(Request $request)
    {
        $this->params = new ParamsStatistics($request);
        $this->params->initConsolidated();
        $mainConsolidated = new MainConsolidated($this->params);
        $response = $mainConsolidated->create();

        return $response;

    }

    /**
     * Obtiene periodos académico de acuerdo a la jornada de cada grupo
     */
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

    public function getPeriodsBySection(Request $request)
    {

        $periodsWorkingDay = DB::table('section_periods')
            ->select(
                'periods.name as periods_name', 'periods.id as periods_id',
                'section_periods.start_date', 'section_periods.end_date', 'section_periods.percent', 'section_periods.id as working_day_periods_id',
                'periods_state.name as periods_state_name', 'periods_state.id as periods_state_id'
            )
            ->join('periods_state', 'periods_state.id', '=', 'section_periods.periods_state_id')
            ->join('periods', 'periods.id', '=', 'section_periods.periods_id')
            ->where('section_periods.section_id', '=', $request->section_id)
            ->get();

        return $periodsWorkingDay;

    }

    public function getInstitutionOfTeacher(Request $request)
    {
        $institution = Institution::where('id', '=', $this->institution->id)->get();
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

    public function getPositionStudents(Request $request)
    {

        $enrollments = DB::table('notes_final')
            ->select('enrollment.id', 'student.last_name', 'student.name',
                DB::raw('ROUND(SUM(notes_final.value)/SUM(notes_final.value>0), 2) average'),
                DB::raw('SUM(notes_final.`value`>0) tav'))
            ->join('evaluation_periods', 'evaluation_periods.id', '=', 'notes_final.evaluation_periods_id')
            ->join('enrollment', 'enrollment.id', '=', 'evaluation_periods.enrollment_id')
            ->join('student', 'student.id', '=', 'enrollment.student_id')
            ->join('institution', 'institution.id', '=', 'enrollment.institution_id')
            ->join('schoolyears', 'schoolyears.id', '=', 'enrollment.school_year_id')
            ->join('group_assignment', 'group_assignment.enrollment_id', '=', 'enrollment.id')
            ->join('group', 'group.id', '=', 'group_assignment.group_id')
            ->join('headquarter', 'headquarter.id', '=', 'group.headquarter_id')
            ->join('group_pensum', 'group_pensum.group_id', '=', 'group.id')
            ->join('areas', 'areas.id', '=', 'group_pensum.areas_id')
            ->join('asignatures', 'asignatures.id', '=', 'group_pensum.asignatures_id')
            ->whereColumn(
                [
                    ['headquarter.institution_id', '=', 'institution.id'],
                    ['group_pensum.asignatures_id', '=', 'evaluation_periods.asignatures_id']
                ]
            )
            ->where('group.id', '=', $request->group_id)
            ->where('institution.id', '=', $this->institution->id)
            ->where('schoolyears.id', '=', '1')
            ->where('evaluation_periods.periods_id', '=', $request->periods_id)
            ->groupBy('enrollment.id')
            ->orderBy('average', 'DESC')
            ->get();

        $positions = GenerateRating::createVectorRating($enrollments);

        return $positions;
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

    public function getAreasGroupPensum(Request $request)
    {
        $asignatures = DB::table('group_pensum')
            ->select(
                'areas.abbreviation', 'areas.name',
                'group_pensum.areas_id as asignatures_id', 'group_pensum.ihs')
            ->join('areas', 'areas.id', '=', 'group_pensum.areas_id')
            ->where('group_pensum.group_id', '=', $request->group_id)
            ->groupBy('areas.id')
            ->get();

        return $asignatures;
    }

    public static function getQueryGetSubjects(Request $request)
    {
        if ($request->is_filter_areas == "true") {
            return GroupPensum::getAreasByGroupX($request->group_id);
        }
        if ($request->is_filter_areas != "true") {
            return GroupPensum::getAsignaturesByGroupX($request->group_id);
        }
    }


}
