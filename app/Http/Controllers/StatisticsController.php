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
use App\Subgroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;


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

        $positions = $this->averageStudents($enrollments);

        return $positions;
    }

    public function averageStudents($arrayStudentAverage)
    {

        #
        $count = 0;

        #Array donde se va almacenar objetos de estudiantes de arrayStudentAverage, pero con una estructra un poco modificada
        $vectorOfStudents = array();

        #En este vector se va a guardar el número de asignaras evaluada por cada estudiante
        $vectorNumberAsignatures = array();

        foreach ($arrayStudentAverage as $key => $value) {
            $vectorStudent = array(
                'id' => $value->id,
                'last_name' => $value->last_name,
                'name' => $value->name,
                'average' => $value->average,
                'tav' => $value->tav
            );

            #Se guarda la nueva estructura en el vector por cada estudiante
            $vectorOfStudents[$count] = $vectorStudent;

            # Se guarda el tav de asignatura del estudiante i o count..
            $vectorNumberAsignatures[$count] = $value->tav;

            $count++;
        }

        #Obtengo y almaceno el número maximo de asignaturas evaluadas
        if(count($vectorNumberAsignatures)){
            $numberMaxOfAsignatures = max($vectorNumberAsignatures);
        }else{
            $numberMaxOfAsignatures = 1;
        }



        #Este es un nuevo vector donde se va a guardar los mismo estudiantes pero con el promedio levemente modificado
        $vectorOfStudentsAux = array();
        foreach ($vectorOfStudents as $value) {

            #Esta formula da como resultado un promedio auxiliar,
            #Nos soluciona el problema de aquellos estudiantes que tienen un promedio alto pero con menor
            #asignaturas evaluadas
            $averageAux = (($value['average'] * $value['tav']) / $numberMaxOfAsignatures);

            $vectorStudent = array(
                'id' => $value['id'],
                'last_name' => $value['last_name'],
                'name' => $value['name'],
                'averageAux' => $averageAux,
                'average' => $value['average'],
                'tav' => $value['tav']
            );
            #usamos el id de estudiante como el indice del vector
            $vectorOfStudentsAux[$value['id']] = $vectorStudent;
        }

        $vectorOfStudentsAux = $this->orderMultiDimensionalArray($vectorOfStudentsAux, 'averageAux', true);
        $vectorOfStudentsAux = $this->generateRating($vectorOfStudentsAux);
        $vectorOfStudentsAux = $this->orderMultiDimensionalArray($vectorOfStudentsAux, 'rating', false);
        return $vectorOfStudentsAux;
    }

    function orderMultiDimensionalArray($toOrderArray, $field, $inverse = false)
    {
        $position = array();
        $newRow = array();
        foreach ($toOrderArray as $key => $row) {
            $position[$key] = $row[$field];
            $newRow[$key] = $row;
        }
        if ($inverse) {
            arsort($position);
        } else {
            asort($position);
        }
        $returnArray = array();
        foreach ($position as $key => $pos) {
            $returnArray[] = $newRow[$key];
        }
        return $returnArray;
    }

    private function generateRating($vectorOfStudentsAux)
    {
        #variable con la que voy asignar el puesto de cada estudiante
        $countAux = 1;

        #promedio auxiliar que comienza en cero
        $averageAux = 0;
        $vectorRating = array();

        #Vamos a recorrer el vector auxiliar de estudiante, ya esta ordenado segun al promdedio modificado
        foreach ($vectorOfStudentsAux as $key => $value) {

            #Si es mayor
            if ($value['averageAux'] > $averageAux) {
                $vectorOfStudent = array(
                    'id' => $value['id'],
                    'last_name' => $value['last_name'],
                    'name' => $value['name'],
                    'rating' => $countAux,
                    'average' => $value['average'],
                    'tav' => $value['tav']
                );
                $averageAux = $value['averageAux'];
                $vectorRating[$value['id']] = $vectorOfStudent;
                $countAux++;
            }
            #Si es igual
            if ($value['averageAux'] == $averageAux) {
                $vectorOfStudent = array(
                    'id' => $value['id'],
                    'last_name' => $value['last_name'],
                    'name' => $value['name'],
                    'rating' => $countAux - 1,
                    'average' => $value['average'],
                    'tav' => $value['tav']
                );
                $averageAux = $value['averageAux'];
                $vectorRating[$value['id']] = $vectorOfStudent;
            }
            #Si es menor
            if ($value['averageAux'] < $averageAux) {
                $vectorOfStudent = array(
                    'id' => $value['id'],
                    'last_name' => $value['last_name'],
                    'name' => $value['name'],
                    'rating' => $countAux,
                    'average' => $value['average'],
                    'tav' => $value['tav']
                );
                $averageAux = $value['averageAux'];
                $vectorRating[$value['id']] = $vectorOfStudent;
                $countAux++;
            }

        }

        return $vectorRating;
    }

    public function getAsignaturesGroupPensum(Request $request)
    {
        if ($request->isSubGroup == "false") {
            $asignatures = DB::table('group_pensum')
                ->select(
                    'asignatures.abbreviation', 'asignatures.name',
                    'group_pensum.asignatures_id', 'group_pensum.ihs', 'group_pensum.order', 'group_pensum.percent')
                ->join('asignatures', 'asignatures.id', '=', 'group_pensum.asignatures_id')
                ->where('group_pensum.group_id', '=', $request->group_id)
                ->get();

            return $asignatures;
        } else {
            $asignatures = DB::table('sub_group_pensum')
                ->select(
                    'asignatures.abbreviation', 'asignatures.name',
                    'sub_group_pensum.asignatures_id', 'sub_group_pensum.ihs', 'sub_group_pensum.order', 'sub_group_pensum.percent')
                ->join('asignatures', 'asignatures.id', '=', 'sub_group_pensum.asignatures_id')
                ->where('sub_group_pensum.sub_group_id', '=', $request->group_id)
                ->get();

            return $asignatures;
        }

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

    private function getNotesFinal($params)
    {

        $paramsSearch = (object)array(
            'group_id' => $params->group_id,
            'institution_id' => $this->institution->id,
            'periods_id' => $params->periods_id
        );

        if ($params->isFilterAreas == "true") {
            return NotesFinal::getNotesFilterByAreas($paramsSearch);
        }
        if ($params->isFilterAreas != "true") {
            return NotesFinal::getNotesFilterByAsignatures($paramsSearch);
        }
    }

    private function createStructConsolidated($enrollments, $notes_final, $params)
    {
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
            $position_enrollment = $this->getPositionStudents($params);
            foreach ($position_enrollment as $key => $p_enroll) {
                if ($enrollment->id == $p_enroll['id']) {
                    $enrollment->averageForPeriods = $p_enroll;
                }
            }*/



            $enrollment->notes_final = $collection_notes_final;

            array_push($collection, $enrollment);
        }
        //$positions = $this->averageStudents($collection);

        return $collection;
    }

    public function getConsolidated(Request $request)
    {

        $enrollments = Group::enrollmentsByGroup($this->institution->id, $request->group_id);
        $notes_final = $this->getNotesFinal($request);
        $collection = $this->createStructConsolidated($enrollments, $notes_final, $request);

        return $collection;
    }
}
