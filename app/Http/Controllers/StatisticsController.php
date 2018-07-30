<?php

namespace App\Http\Controllers;

use App\Asignature;
use App\Enrollment;
use App\EvaluationPeriod;
use App\Grade;
use App\Group;
use App\GroupPensum;
use App\Institution;
use App\MessagesExpressions;
use App\NoAttendance;
use App\Note;
use App\NotesFinal;
use App\NotesParametersPerformances;
use App\Performances;
use App\ScaleEvaluation;
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
    private $vectorNotes = [];
    private $vectorPeriods = [];
    private $vectorSubjects = [];
    private $vectorEnrollments = [];
    private $minScaleObject = null;
    private $numPeriods = 0;

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
        $group = Group::findOrfail($request->group_id);
        $this->vectorNotes = $this->queryGetNotesFinal($request);
        $this->vectorSubjects = $this->queryGetSubjectsNotes($request);
        $this->minScaleObject = ScaleEvaluation::getMinScale($this->institution);
        $this->vectorPeriods = $this->getPeriodsByWorkingDay($group->working_day_id);
        $this->vectorEnrollments = Group::enrollmentsByGroup($this->institution->id, $group->id);

        $this->minScaleObject->rank_end += 0.1;
        $this->numPeriods = count($this->vectorPeriods);

        $this->createVectorConsolidated();

        return $this->vectorEnrollments;
    }


    /**
     * Obtiene notas finales por asignatura o áreas
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
     * Obtiene asignaturas o áreas
     */
    private function queryGetSubjectsNotes($params)
    {
        $paramsSearch = (object)array(
            'group_id' => $params->group_id,
            'institution_id' => $this->institution->id,
        );

        if ($params->isFilterAreas == "true") {
            return GroupPensum::getAreasByGroup($paramsSearch);
        }
        if ($params->isFilterAreas != "true") {

            return GroupPensum::getAsignaturesByGroup($paramsSearch);
        }
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


    /**
     * Construye un vector con toda la información necesaria para el consolidado
     */
    private function createVectorConsolidated()
    {
        foreach ($this->vectorPeriods as $period) {
            $this->addPropertyToEnrollmentPeriodsEvaluated($period);
        }

        $this->addPropertiesToEnrollments();
        $this->addPropertyToEnrollmentsAccumulatedAsignatures();
        $this->addPropertyToEnrollmentsRequiredValuation();
    }

    private function addPropertyToEnrollmentsRequiredValuation()
    {
        foreach ($this->vectorEnrollments as $enrollment) {
            $required = [];

            $this->calculateRequiredValuation($enrollment, $required);

            if (!isset($enrollment->requiredValuation)) {
                $enrollment->requiredValuation = [];
            }
            $enrollment->requiredValuation = $required;
        }
    }


    private function calculateRequiredValuation($enrollment, &$required)
    {
        foreach ($this->vectorSubjects as $subject) {
            $valueRequired = 0;

            foreach ($enrollment->accumulatedSubjects as $rowAccumulated) {
                if ($subject->asignatures_id == $rowAccumulated->asignatures_id) {
                    $rest = $this->minScaleObject->rank_end - $rowAccumulated->average;
                    $missingPeriod = $this->numPeriods - $rowAccumulated->tav;
                    $divide = 0;

                    if ($missingPeriod > 0) {
                        $divide = $rest / $missingPeriod;
                        $index = count($rowAccumulated->periods);
                        $percent = $this->vectorPeriods[$index]->percent / 100;
                        $valueRequired = $divide / $percent;
                    }

                }

            }

            $data = (object)array(
                'required' => $valueRequired,
                'name' => $subject->name,
                'asignatures_id' => $subject->asignatures_id,
            );

            array_push($required, $data);
        }
    }

    /**
     * Adicciona a cada estudiante matriculado un vector con las notas acumuladas de cada asignatura
     */
    private function addPropertyToEnrollmentsAccumulatedAsignatures()
    {
        foreach ($this->vectorEnrollments as $enrollment) {
            $accumulated = [];

            $this->calculateAccumulatedAverage($enrollment, $accumulated);

            if (!isset($enrollment->accumulatedSubjects)) {
                $enrollment->accumulatedSubjects = [];
            }
            $enrollment->accumulatedSubjects = $accumulated;
        }
    }

    private function calculateAccumulatedAverage($enrollment, &$accumulated)
    {
        foreach ($this->vectorSubjects as $subject) {
            $sum = 0;
            $periodsEvaluated = [];
            $countTavAsignatures = 0;
            foreach ($enrollment->evaluatedPeriods as $rowEvaluatedPeriod) {
                foreach ($rowEvaluatedPeriod['notes'] as $note) {
                    if ($subject->asignatures_id == $note->asignatures_id) {
                        $info = (object)array(
                            'period_id' => $rowEvaluatedPeriod['period_id'],
                            'percent' => $rowEvaluatedPeriod['percent']
                        );
                        array_push($periodsEvaluated, $info);
                        $sum += $this->calculateAccumulatedNotes($note, $rowEvaluatedPeriod, $countTavAsignatures);
                    }
                }
            }
            $data = (object)array(
                'average' => $sum,
                'name' => $subject->name,
                'tav' => $countTavAsignatures,
                'periods' => $periodsEvaluated,
                'asignatures_id' => $subject->asignatures_id,
            );
            array_push($accumulated, $data);
        }
    }

    private function calculateAccumulatedNotes($note, $rowEvaluatedPeriod, &$countTavAsignatures)
    {

        $value = $this->processNote($note->value, $note->overcoming);
        $this->generateTav($countTavAsignatures, $value);
        $percent = $rowEvaluatedPeriod['percent'] / 100;
        return ($value * $percent);
    }

    /**
     * Modifica vectorEnrollments, agregrando la propiedad evaluatedPeriods, se ejecuta
     * por cada periodo recorrido
     */
    private function addPropertyToEnrollmentPeriodsEvaluated($period)
    {

        foreach ($this->vectorEnrollments as $enrollment) {

            $vectorNotesEvaluatedPeriod = $this->getVectorNotesToEnrollment($enrollment, $period);

            $this->savePeriodEvaluatedToEnrollment($enrollment, $vectorNotesEvaluatedPeriod, $period);

        }
    }


    /**
     * Guarda información del periodo evaluado por estudiante
     */
    private function savePeriodEvaluatedToEnrollment(&$enrollment, $vectorNotesEvaluatedPeriod, $period)
    {
        # Se crea un nuevo atributo al objeto enrollment
        if (!isset($enrollment->evaluatedPeriods)) {
            $enrollment->evaluatedPeriods = [];
        }

        $arrayDataEnrollment = array
        (
            "rating" => 0,
            "percent" => $period->percent,
            "period_id" => $period->periods_id,
            "tav" => $vectorNotesEvaluatedPeriod->tav,
            "average" => $vectorNotesEvaluatedPeriod->average,
            "notes" => $vectorNotesEvaluatedPeriod->vectorNotesToEnrollment,
        );

        array_push($enrollment->evaluatedPeriods, $arrayDataEnrollment);
    }


    /**
     * Modifica vectorEnrollments, agrega la propiedad rating
     */
    public function addPropertiesToEnrollments()
    {
        $vectorDataBasicPeriodsEvaluated = $this->createVectorDataPeriodsEvaluated();

        foreach ($vectorDataBasicPeriodsEvaluated as $rowDataBasicPeriodEvaluated) {
            #
            $this->createProperties($rowDataBasicPeriodEvaluated);
        }
    }


    /**
     * Recorre cada estudiante para asignarle dos propiedades:
     * enrollment.accumulatedAverage
     * enrollment.evaluatedPeriods.rating
     */
    private function createProperties($rowDataBasicPeriodEvaluated)
    {
        $vectorRating = $this->createVectorRating($rowDataBasicPeriodEvaluated->enrollments);

        foreach ($this->vectorEnrollments as &$enrollment) {

            foreach ($enrollment->evaluatedPeriods as &$enrollPeriodEvaluated) {

                if ($rowDataBasicPeriodEvaluated->period_id == $enrollPeriodEvaluated['period_id']) {

                    $this->addAccumulatedAverageToEnrollment($enrollment, $rowDataBasicPeriodEvaluated, $enrollPeriodEvaluated);

                    $this->addRatingToEnrollmentEvaluatedPeriods($enrollment, $enrollPeriodEvaluated, $vectorRating);
                }

            }
        }
    }


    /**
     * Agrega a cada estudiante el promedio general acumulado
     */
    private function addAccumulatedAverageToEnrollment(&$enrollment, $rowDataBasicPeriodEvaluated, $enrollPeriodEvaluated)
    {
        if (!isset($enrollment->accumulatedAverage)) {
            $enrollment->accumulatedAverage = 0;
        }
        $enrollment->accumulatedAverage += ($enrollPeriodEvaluated['average'] * ($rowDataBasicPeriodEvaluated->percent / 100));
    }

    /**
     * Agrega a cada estudiante por periodo evaluado el puesto académico ocupado
     */
    private function addRatingToEnrollmentEvaluatedPeriods($enrollment, &$enrollPeriodEvaluated, $vectorRating)
    {
        foreach ($vectorRating as $rowEnrollmentRating) {

            if ($enrollment->id == $rowEnrollmentRating['id']) {
                $enrollPeriodEvaluated['rating'] = $rowEnrollmentRating['rating'];
            }
        }
    }

    /**
     * Crea un vector con los periodos evaluados, y cada periodo tiene un objeto
     * con información básica, como lo es el id del periodo, su porcentaje y los
     * estudiantes con sus repectivas calificaciones correspondiente al periodo
     * indicado
     */
    private function createVectorDataPeriodsEvaluated()
    {
        $vectorDataBasicPeriodsEvaluated = [];
        foreach ($this->vectorPeriods as $period) {
            array_push($vectorDataBasicPeriodsEvaluated, $this->createVectorDataPeriodEvaluated($period));
        }

        return $vectorDataBasicPeriodsEvaluated;
    }

    /**
     * Retorna un vector por periodo de los estudiantes evaluados con la siguiente información:
     * percent, period_id, enrollments
     * Este es util cuando se necesita generar los puestos de estudiante por cada periodo.
     */
    private function createVectorDataPeriodEvaluated($period)
    {
        $vectorDataBasicPeriodEvaluated = [];
        foreach ($this->vectorEnrollments as $enrollment) {

            foreach ($enrollment->evaluatedPeriods as $enrollPeriodEvaluated) {
                if ($enrollPeriodEvaluated['period_id'] == $period->periods_id) {
                    $dataEnrollment = (object)array
                    (
                        'id' => $enrollment->id,
                        'tav' => $enrollPeriodEvaluated['tav'],
                        'name' => $enrollment->student_name,
                        'average' => $enrollPeriodEvaluated['average'],
                        'last_name' => $enrollment->student_last_name,
                    );
                    array_push($vectorDataBasicPeriodEvaluated, $dataEnrollment);
                }
            }

        }
        $vectorBasicPeriodEvaluated = (object)array(
            'percent' => $period->percent,
            'period_id' => $period->periods_id,
            'enrollments' => $vectorDataBasicPeriodEvaluated,
        );

        return $vectorBasicPeriodEvaluated;
    }


    /**
     * Recibe un vector de estudiantes con datos básicos:
     * average, tav, name, id para generar el vector de puestos correspondiente
     */
    private function createVectorRating($arrayStudentAverage)
    {

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

        $vectorOfStudentsAux = $this->sortVector($vectorOfStudentsAux, 'averageAux', true);
        $vectorOfStudentsAux = $this->generateRating($vectorOfStudentsAux);
        $vectorOfStudentsAux = $this->sortVector($vectorOfStudentsAux, 'rating', false);
        return $vectorOfStudentsAux;
    }


    /**
     * Genera un vector por estudiante y periodo evaluado con sus respectivas notas
     */
    private function getVectorNotesToEnrollment(&$enrollment, $period)
    {
        $vectorNotesToEnrollment = [];
        $sumNotes = 0;
        $sumTav = 0;
        $average = 0;
        $sum = 0;
        foreach ($this->vectorNotes as $keyNotes => $note) {

            # Si la nota corresponde al estudiante actual en el ciclo, y al periodo actual
            if ($enrollment->id == $note->enrollment_id) {

                if ($period->periods_id == $note->periods_id) {
                    $sum = $this->processNote($note->value, $note->overcoming);
                    $sumNotes += $sum;

                    $this->generateTav($sumTav, $this->processNote($note->value, $note->overcoming));
                    array_push($vectorNotesToEnrollment, $note);
                    unset($this->vectorNotes[$keyNotes]);
                }
            }

        }

        $average = $this->generateAverage($sumTav, $sumNotes);


        return (object)array(
            'vectorNotesToEnrollment' => $vectorNotesToEnrollment,
            'tav' => $sumTav,
            'average' => round($average, 2)
        );
    }

    /**
     * Aumenta el contador si la nota introducidad es mayor a cero, el valor es
     * asignado a un parametro por referencia
     */
    private function generateTav(&$sumTav, $note)
    {
        if ($note > 0) {
            $sumTav++;
        }
    }

    /**
     * Retorna el promedio general: sumatoria de notas dividido en el número de notas
     * mayor a cero
     */
    private function generateAverage($sumTav, $sumNotes)
    {
        if ($sumTav > 0) {
            return $average = $sumNotes / $sumTav;
        }
    }

    /**
     * Determina que nota regresar, si se ha hecho recuperación.
     */
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


    private function sortVector($toOrderArray, $field, $inverse = false)
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


}
