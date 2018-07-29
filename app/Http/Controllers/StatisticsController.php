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
use App\Workingday;
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

    /**
     * Obtiene un array con la información para el consolidado solicitado
     */
    public function getConsolidated(Request $request)
    {

        $arrayEnrollments = Group::enrollmentsByGroup($this->institution->id, $request->group_id);
        $group = Group::findOrfail($request->group_id);

        $arrayNotes = $this->queryGetNotesFinal($request);
        $arrayPeriods = $this->getPeriodsByWorkingDay($group->working_day_id);
        $arrayConsolidated = $this->createArrayConsolidated($arrayEnrollments, $arrayNotes, $arrayPeriods);

        return $arrayConsolidated;
    }


    /**
     * Obtiene las notas finales por asignatura o áreas
     */
    private function queryGetNotesFinal($params)
    {
        $paramsSearch = (object)array(
            'group_id' => $params->group_id,
            'institution_id' => $this->institution->id,
        );

        if ($params->isFilterAreas == "true") {
            return NotesFinal::getNotesFilterByAreas($paramsSearch);
        }
        if ($params->isFilterAreas != "true") {

            return NotesFinal::getNotesFilterByAsignatures($paramsSearch);
        }
    }


    /**
     * Obtiene los periodos académico de acuerdo a la jornada de cada grupo
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


    /**
     * Construye un arreglo con toda la información necesaria para el consolidado
     */
    private function createArrayConsolidated($arrayEnrollments, $arrayNotes, $arrayNumberPeriods)
    {
        foreach ($arrayNumberPeriods as $keyPeriod => $period) {
            $this->addPropertyToEnrollmentPeriodsEvaluated($arrayEnrollments, $arrayNotes, $period->periods_id);
        }

        $this->addPropertyToEnrollmentsRating($arrayEnrollments, $arrayNumberPeriods);
        return $arrayEnrollments;
    }


    /**
     * Modifica el arreglo arrayEnrollments, agregandole la propiedad Periodos Evaluados, se ejecuta
     * por cada periodo recorrido
     */
    private function addPropertyToEnrollmentPeriodsEvaluated(&$arrayEnrollments, $arrayNotes, $period_id)
    {

        foreach ($arrayEnrollments as $keyEnrollment => $enrollment) {

            $vectorEvaluatedPeriod = $this->getVectorNotesToEnrollment($enrollment, $arrayNotes, $period_id);
            $this->savePeriodEvaluatedToEnrollment($enrollment, $vectorEvaluatedPeriod, $period_id);

        }
    }


    /**
     * Guarda información del periodo evaluado por estudiante y periodo
     */
    private function savePeriodEvaluatedToEnrollment(&$enrollment, $vectorEvaluatedPeriod, $period_id)
    {
        # Se crea un nuevo atributo al objeto enrollment
        if (!isset($enrollment->evaluatedPeriods)) {
            $enrollment->evaluatedPeriods = [];
        }

        $arrayDataEnrollment = array
        (
            "rating" => 0,
            "period_id" => $period_id,
            "tav" => $vectorEvaluatedPeriod->tav,
            "average" => $vectorEvaluatedPeriod->average,
            "notes" => $vectorEvaluatedPeriod->arrayNotesOfEnrollment,
        );

        array_push($enrollment->evaluatedPeriods, $arrayDataEnrollment);
    }


    /**
     * Modifica el arreglo arrayEnrollments, agregandole la propiedad Rating
     */
    public function addPropertyToEnrollmentsRating(& $arrayEnrollments, $arrayNumberPeriods)
    {

        $vectorBasicDataPeriods = $this->createVectorBasicData($arrayEnrollments, $arrayNumberPeriods);

        foreach ($vectorBasicDataPeriods as $rowBasicDataPeriod) {


            $vectorRating = $this->createVectorRating($rowBasicDataPeriod->enrollments);
            $this->addPropertiesToEnrollments($arrayEnrollments,$rowBasicDataPeriod, $vectorRating);

        }


    }


    private function addPropertiesToEnrollments(&$arrayEnrollments, $rowBasicDataPeriod, $vectorRating)
    {

        foreach ($arrayEnrollments as $keyEnrollment => &$enrollment) {

            foreach ($enrollment->evaluatedPeriods as &$periodEvaluation) {

                if ($periodEvaluation['period_id'] == $rowBasicDataPeriod->period_id) {

                    $this->addAccumulatedAverageToEnrollment($enrollment,$rowBasicDataPeriod, $periodEvaluation);

                    $this->addRatingToEnrollment($enrollment, $periodEvaluation, $vectorRating);
                }

            }
        }
    }

    private function addAccumulatedAverageToEnrollment(&$enrollment, $rowBasicDataPeriod, $periodEvaluation){
        if (!isset($enrollment->acumulated)) {
            $enrollment->acumulated = 0;
        }
        $enrollment->acumulated += ($periodEvaluation['average'] * ($rowBasicDataPeriod->percent / 100));
    }

    private  function addRatingToEnrollment(&$enrollment, &$periodEvaluation, $vectorRating){
        foreach ($vectorRating as $rowEnrollmentRating) {

            if ($enrollment->id == $rowEnrollmentRating['id']) {
                $periodEvaluation['rating'] = $rowEnrollmentRating['rating'];
            }
        }
    }

    /**
     * Crea un vector con los periodos evaluados, y cada periodo tiene un objeto
     * con información básica, como lo es el id del periodo, su porcentaje y los
     * estudiantes con sus repectivas calificaciones correspondiente al periodo
     * indicado
     */
    private function createVectorBasicData($arrayEnrollments, $arrayNumberPeriods)
    {
        $vectorBasicData = [];
        foreach ($arrayNumberPeriods as $keyPeriod => $period) {
            array_push($vectorBasicData, $this->createBasicData($arrayEnrollments, $period));
        }

        return $vectorBasicData;
    }

    /**
     * Agrupa todos los estudiantes evaluados en un periodo correspondiente, y
     * retorna una estructura basica: porcentaje del periodo, id y los estudiantes con sus notas
     */
    private function createBasicData($arrayEnrollments, $period)
    {
        $vectorBasicData = [];
        foreach ($arrayEnrollments as $keyEnrollment => $enrollment) {

            foreach ($enrollment->evaluatedPeriods as $keyPeriodsEvaluation => $periodEvaluation) {
                if ($periodEvaluation['period_id'] == $period->periods_id) {
                    $dataEnrollment = (object)array
                    (
                        'id' => $enrollment->id,
                        'tav' => $periodEvaluation['tav'],
                        'name' => $enrollment->student_name,
                        'average' => $periodEvaluation['average'],
                        'last_name' => $enrollment->student_last_name,
                    );
                    array_push($vectorBasicData, $dataEnrollment);
                }
            }

        }
        $data = (object)array(
            'percent' => $period->percent,
            'period_id' => $period->periods_id,
            'enrollments' => $vectorBasicData,
        );

        return $data;
    }

    private function createVectorRating($arrayStudentAverage)
    {
        $vectorEnrollments = [];

        #Array donde se va almacenar objetos de estudiantes de arrayStudentAverage, pero con una estructra un poco modificada
        $vectorOfStudents = array();

        #En este vector se va a guardar el número de asignaras evaluada por cada estudiante
        $vectorNumberAsignatures = array();

        #
        $count = 0;

        foreach ($arrayStudentAverage as $key => $value) {
            $vectorStudent = array(
                'id' => $value->id,
                'last_name' => $value->last_name,
                'name' => $value->name,
                'average' => $value->average,
                'tav' => $value->tav
            );

            #Se guarda la nueva estructura en el vector por cada estudiante
            $vectorOfStudents[$key] = $vectorStudent;

            # Se guarda el tav de asignatura del estudiante i o count..
            $vectorNumberAsignatures[$key] = $value->tav;

        }

        #Obtengo y almaceno el número maximo de asignaturas evaluadas
        if (count($vectorNumberAsignatures)) {
            if (max($vectorNumberAsignatures) > 0) {
                $numberMaxOfAsignatures = max($vectorNumberAsignatures);
            } else {
                $numberMaxOfAsignatures = 1;
            }
        } else {
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

        $positions = $this->createVectorRating($enrollments);

        return $positions;
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


    private function getVectorNotesToEnrollment($enrollment, $notes, $period_id)
    {
        $arrayNotesOfEnrollment = [];
        $sumNotes = 0;
        $sumTav = 0;
        $average = 0;
        foreach ($notes as $keyNotes => $note) {

            # Si la nota corresponde al estudiante actual en el ciclo, y al periodo actual
            if ($enrollment->id == $note->enrollment_id && $period_id == $note->periods_id) {

                $sumNotes += $this->processNote($note->value, $note->overcoming);
                if ($this->processNote($note->value, $note->overcoming) > 0) {
                    $sumTav++;
                }
                array_push($arrayNotesOfEnrollment, $note);
                unset($notes[$keyNotes]);
            }
        }

        if ($sumTav > 0) {
            $average = $sumNotes / $sumTav;
        }

        return (object)array(
            'arrayNotesOfEnrollment' => $arrayNotesOfEnrollment,
            'tav' => $sumTav,
            'average' => round($average, 2)
        );
    }

    private function processNote($note, $overcoming)
    {
        $noteAux = 0;
        $overcomingAux = 0;
        if ($note > 0) {
            if ($overcoming != null && $overcoming > 0) {
                $overcomingAux = $overcoming;
            }
            $noteAux = $note;

        }

        if ($noteAux > $overcomingAux)
            return $noteAux;
        else
            return $overcomingAux;
    }


}
