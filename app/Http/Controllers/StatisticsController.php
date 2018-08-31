<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupPensum;
use App\Helpers\Statistics\Percentage\Dispatchers\JsonPercentage;
use App\ScaleEvaluation;
use Auth;
use App\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Helpers\Statistics\MainStatistics;
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
        $request->institution = $this->institution;
        $this->params = new ParamsStatistics($request);
        $this->params->initConsolidated();
        $mainStatistics = new MainStatistics($this->params);
        $response = $mainStatistics->createConsolidate();

        return $response;

    }

    public function getPercentage(Request $request){
        $request->institution = $this->institution;
        $this->params = new ParamsStatistics($request);
        $this->params->initConsolidated();
        $mainStatistics = new MainStatistics($this->params);
        $response = $mainStatistics->createPercentage();
        //return
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
        $groups = Group::getGroupsByGrade($this->institution->id,$request->grade_id);

        return $groups;
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
