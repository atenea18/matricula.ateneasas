<?php

namespace App\Helpers;

use App\FinalReport;
use App\FinalReportAsignature;
use Illuminate\Http\Request;

use App\Traits\ConvertFormant;

use App\Enrollment;
use App\Group;
use App\GroupPensum;
use App\SubGroupPensum;
use App\EvaluationPeriod;
use App\NotesFinal;
use App\PeriodWorkingday;
use App\ScaleEvaluation;

class Notebook
{
    use ConvertFormant;

    private $request = array();

    private $periods = array();
    private $institution = array();
    private $config = array();
    private $scaleEvaluation = array();
    private $evaluation_parameters = array();

    private $performance_specials = [8, 14];

    private $pensums_areas = array();
    private $pensums_asignatures = array();

    private $group = array();
    private $manager = array();
    private $current_period = array();
    private $general_observation = array();
    private $general_report = array();
    private $average_areas = array();
    private $enrollment = array();
    public $report_asignatures = [];
    public $report_areas = [];
    public $report_final = [];

    //
    public $noteBook;

    public function __construct(Request $request, $institution)
    {
        $this->request = $request;
        $this->institution = $institution;

        // Aplicacion la configuracion de las variables
        $this->config();

        //
        $this->executeAllQueryGlobal();
    }

    private function executeAllQueryGlobal()
    {

        $this->group = Group::findOrFail($this->request->group);

        //Reporte Asignaturas
        $this->report_asignatures = FinalReportAsignature::getEnrollmentsByGroup($this->group->id);
        $this->report_areas = FinalReportAsignature::getEnrollmentsAreasByGrade($this->group->grade_id);
        //dd($this->report_areas)
        $this->report_final = FinalReport::getEnrollmentsByGroup($this->group->id);

        $this->manager = (!is_null($this->group->director()->first())) ? $this->group->director()->first()->manager : null;

        $this->current_period = PeriodWorkingday::findOrFail($this->request->period);

    }

    private function getPensumsAreas()
    {

        $groupEnrollment = $this->enrollment->group()->first();
        $subgroupEnrollment = $this->enrollment->subgroups()->first();

        $groupPensum = array();
        $SubGroupPensum = array();

        if (!is_null($groupEnrollment)) {
            $groupPensum = $groupEnrollment->pensums()
                //->where('subjects_type_id', '!=', 2)
                ->where('areas_id', '!=', 62)
                ->with('area')
                ->with('subjectType')
                ->with('teacher.manager')
                ->orderBy('order', 'asc')
                ->get()
                ->unique('areas_id')
                ->values();

            foreach ($groupPensum as $key => $pensum) {
                array_push($this->pensums_areas, $pensum);
            }
        }

        if (!is_null($subgroupEnrollment)) {
            
            $subgroupPensum = SubGroupPensum::where('sub_group_id', '=', $subgroupEnrollment->id)
                ->with('area')
                ->with('subjectType')
                ->orderBy('order', 'asc')
                ->get()
                ->unique('areas_id')
                ->values();

            foreach ($subgroupPensum as $key => $pensum) {
                array_push($this->pensums_areas, $pensum);
            }
            
        }
        //dd($this->pensums_areas);
        return $this->pensums_areas;
    }

    private function getPensumsAsignatgures()
    {
        $groupEnrollment = $this->enrollment->group()->first();
        $subgroupEnrollment = $this->enrollment->subgroups()->first();

        $groupPensum = array();
        $SubGroupPensum = array();

        if (!is_null($groupEnrollment)) {
            $groupPensum = $groupEnrollment->pensums()
                ->where('subjects_type_id', '!=', 2)
                ->with('area')
                ->with('subjectType')
                ->with('teacher.manager')
                ->orderBy('order', 'asc')
                ->get();

            foreach ($groupPensum as $key => $pensum) {
                array_push($this->pensums_asignatures, $pensum);
            }
        }

        if (!is_null($subgroupEnrollment)) {
            $subgroupPensum = SubGroupPensum::where('sub_group_id', '=', $subgroupEnrollment->id)
                ->with('area')
                ->with('subjectType')
                ->orderBy('order', 'asc')
                ->get();

            foreach ($subgroupPensum as $key => $pensum) {
                array_push($this->pensums_asignatures, $pensum);
            }
        }
        return $this->pensums_asignatures;
    }

    private function getPeriods()
    {

        $periods = $this->institution->periods()
            ->with('period')
            ->with('schoolyear')
            ->with('state')
            ->with('workingday')
            ->where([
                ['working_day_id', '=', $this->group->working_day_id],
                ['school_year_id', '=', 1]
            ])
            ->get();

        return $periods;
    }

    private function config()
    {
        $this->config = [
            'showTeacher' => (isset($this->request['showTeacher'])) ? true : false,
            'valorationScale' => (isset($this->request['valorationScale'])) ? true : false,
            'showPerformance' => (isset($this->request['showPerformance'])) ? 'indicators' : 'asignature',
            'areasDisabled' => (isset($this->request['areasDisabled'])) ? true : false,
            'doubleFace' => (isset($this->request['doubleFace'])) ? true : false,
            'generalReportPeriod' => (isset($this->request['generalReportPeriod'])) ? true : false,
            'showFaces' => (isset($this->request['showFaces'])) ? true : false,
            'combinedEvaluation' => (isset($this->request['CombinedEvaluation'])) ? true : false,
            'NumberValoration' => (isset($this->request['NumberValoration'])) ? true : false,
            'tableDetail' => (isset($this->request['tableDetail'])) ? true : false,
            'performanceRating' => (isset($this->request['performanceRating'])) ? true : false,
            'includeIF' => (isset($this->request['includeIF'])) ? true : false,
            'periodIF' => false,
            'onlyAcademic' => (isset($this->request['academicCheck'])) ? true : false,
            'decimals' => (isset($this->request['decimals'])) ? true : false,
        ];
    }

    public function setEnrollment($enrollment)
    {
        $this->enrollment = $enrollment;
    }

    public function setScaleEvaluation($scaleEvaluation)
    {
        $this->scaleEvaluation = $scaleEvaluation;
    }

    public function setEvaluationParameters($evalParameters)
    {
        $this->evaluation_parameters = $evalParameters;
    }

    public function setGeneralObservation(Enrollment $enrollment)
    {
        return $this->general_observation = $enrollment->observations()->where(
            'period_working_day_id', '=', $this->request->period
        )->first();
    }

    public function setGeneralReport(Enrollment $enrollment)
    {
        return $this->general_report = $enrollment->generalReport()->where(
            'period_working_day_id', '=', $this->request->period
        )->first();
    }

    public function create()
    {

        $this->pensums_areas = $this->getPensumsAreas();
        $this->pensums_asignatures = $this->getPensumsAsignatgures();

        $this->noteBook = array(
            'tittle' => 'INFORME DESCRIPTIVO Y VALORATIVO',
            'tittle_if' => 'INFORME DE EVALUACIÓN FINAL DEL PROCESO FORMATIVO',
            'tittle_general_report' => 'INFORME GENERAL DE PERIODO',
            'current_period' => $this->current_period,
            'date' => (isset($this->request['fecha'])) ? $this->request['fecha'] : date('Y-m-d'),
            'student' => $this->enrollment->student,
            'report_asignatures' => $this->enrollment->report_asignatures,
            'group' => $this->group,
            'director' => $this->manager,
            'grade' => $this->group->grade,
            'headquarter' => $this->group->headquarter,
            'institution' => $this->institution,
            'periods' => array(),
            'config' => $this->config,
            'general_observation' => $this->setGeneralObservation($this->enrollment),
            'general_report' => $this->setGeneralReport($this->enrollment),
            'valueScale' => $this->scaleEvaluation,
            'evaluation_parameters' => $this->evaluation_parameters,
        );

        // Resolvemos las areas, asignaturas y notas de todos los periodos
        foreach ($this->getPeriods() as $key => $periodW) {

            array_push($this->noteBook['periods'], array(
                    'code_working_day_periods' => $periodW->code_working_day_periods,
                    'percent' => $periodW->percent,
                    'start_date' => $periodW->start_date,
                    'end_date' => $periodW->end_date,
                    'working_day_id' => $periodW->working_day_id,
                    'periods_id' => $periodW->periods_id,
                    'periods_state_id' => $periodW->periods_state_id,
                    'school_year_id' => $periodW->school_year_id,
                    'areas' => $this->resolveAreas(
                        $this->enrollment, $this->request->group, $periodW->periods_id
                    ),
                    'average' => $this->resolveAveragePeriod($this->enrollment, 1, $periodW->periods_id),
                )
            );
        }

        return $this->noteBook;
    }

    private function resolveAverageAreas($institution_id, $school_year_id, $period_id)
    {

        $response = array();

        $groupEnrollment = $this->enrollment->group()->first();
        $subgroupEnrollment = $this->enrollment->subgroups()->first();

        $averageGroup = array();
        $averageSubGroup = array();

        if (!is_null($groupEnrollment)) {
            $averageGroup = NotesFinal::getAverageGroupPensum($groupEnrollment->id, $institution_id, $school_year_id, $period_id);

            foreach ($averageGroup as $key => $average) {
                array_push($response, $average);
            }
        }

        if (!is_null($subgroupEnrollment)) {
            $averageSubGroup = NotesFinal::getAverageSubGroupPensum($subgroupEnrollment->id, $institution_id, $school_year_id, $period_id);

            foreach ($averageSubGroup as $key => $average) {
                array_push($response, $average);
            }
        }

        return $response;
    }

    private function resolveAveragePeriod(Enrollment $enrollment, $school_year_id, $period)
    {
        $average = $this->averageStudents(
            NotesFinal::getAverageByGroup($this->group->id, $school_year_id, $this->institution->id, $period),
            $enrollment
        );

        if (!empty($average)) {
            return [
                'average' => $average['average'],
                'tav' => $average['tav'],
                'position' => $average['rating'],
            ];
        }

        return [];
    }

    private function resolveAreas(Enrollment $enrollment, $group_id, $period_id)
    {

        $response = array();
        $averageA = $this->resolveAverageAreas($this->institution->id, 1, $period_id);

        foreach ($this->pensums_areas as $key => $pensum) {
            foreach ($averageA as $keyAA => $average) {
                if ($average->enrollment_id == $enrollment->id && $average->areas_id == $pensum->areas_id) {
                    $note = $this->determineRound($average->average, 1, $this->config['decimals']);
                    array_push(
                        $response,
                        array(
                            'pensum_id' => $pensum->id,
                            'area_id' => $pensum->areas_id,
                            'area' => $pensum->area->name,
                            'abbreviation' => $pensum->abbreviation,
                            'subjects_type_id' => $pensum->subjectType->name,
                            'note' => $note,
                            'valoration' => $this->getScaleByNote($note),
                            'asignatures' => $this->resolveAsignatures(
                                $pensum->areas_id, $enrollment, $period_id
                            )
                        )
                    );
                    break;
                }
            }
        }

        return $response;

    }

    private function resolveAsignatures($area_id, Enrollment $enrollment, $period_id)
    {
        $response = array();

        foreach ($this->pensums_asignatures as $key => $pensum) {

            if ($area_id == $pensum->areas_id) {
                $notes = $this->resolveNotes(
                    $pensum->asignatures_id, $period_id, $enrollment
                );

                $final_note = $this->resolveNote(
                    $pensum->asignatures_id, $period_id, $enrollment, $pensum->id
                );

                if (in_array($this->institution->id, $this->performance_specials)) {
                    $indicators = $this->resolvePerformancesCustom(
                        $pensum->asignatures_id, $period_id, $enrollment, $pensum->id, $notes
                    );
                } else {
                    $indicators = $this->resolvePerformances(
                        $pensum->asignatures_id, $period_id, $enrollment, $pensum->id
                    );
                }
                array_push(
                    $response,
                    array(
                        'asignature_id' => $pensum->asignatures_id,
                        'asignature' => $pensum->asignature->name,
                        'abbreviation' => $pensum->asignature->abbreviation,
                        'ihs' => $pensum->ihs,
                        'teacher' => (!is_null($pensum->teacher)) ? $pensum->teacher : null,
                        'final_note' => $final_note,
                        'notes' => $notes,
                        'indicators' => $indicators,
                    )
                );
            }
        }

        return $response;
    }

    private function resolveNote($asignature_id, $period_id, Enrollment $enrollment, $pensum_id)
    {
        $EvalP = EvaluationPeriod::with('noteFinal')
            ->with('noAttendances')
            ->where([
                'enrollment_id' => $enrollment->id,
                'periods_id' => $period_id,
                'asignatures_id' => $asignature_id
            ])
            ->get();

        $response = array();

        foreach ($EvalP as $key => $ev) {
            $note = 0;
            $overcoming = 0;

            if (!is_null($ev->noteFinal)) {
                $note = $this->determineRound($ev->noteFinal->value, 1, $this->config['decimals']);
                $overcoming = $this->determineRound($ev->noteFinal->overcoming, 1, $this->config['decimals']);
            }

            $valoration = $this->getScaleByNote($note);

            if (in_array($this->institution->id, $this->performance_specials)) {
                $performances = $this->resolveIndicatorsCustom(
                    $asignature_id, $period_id, $pensum_id, $note, $valoration
                );
            } else {
                $performances = $this->resolveIndicators(
                    $asignature_id, $period_id, $enrollment, $pensum_id, $note
                );
            }

            $response = [
                'valoration' => $valoration,
                'value' => $note,
                'overcoming' => $overcoming,
                'performances' => $performances,
                'noAttendances' => $ev->noAttendances->sum('quantity'),
            ];
        }

        return $response;
    }

    private function resolveNotes($asignature_id, $period_id, Enrollment $enrollment)
    {
        $notes = EvaluationPeriod::with('notes')
            ->where([
                'enrollment_id' => $enrollment->id,
                'periods_id' => $period_id,
                'asignatures_id' => $asignature_id
            ])
            ->get()
            ->pluck('notes')
            ->collapse();

        $response = array();

        foreach ($notes as $key => $note) {
            array_push(
                $response,
                [
                    'value' => $this->determineRound($note->value, 1, $this->config['decimals']),
                    'overcoming' => $note->overcoming,
                ]
            );
        }

        return $response;
    }

    private function resolvePerformances($asignature_id, $period_id, Enrollment $enrollment, $pensum_id)
    {

        $notes = EvaluationPeriod::with([
            'notes.noteParameter.notePerformances' => function ($pensum) use ($pensum_id, $period_id) {
                $pensum->where([
                    ['group_pensum_id', '=', $pensum_id],
                    ['periods_id', '=', $period_id]
                ])
                    ->with('performance.message.messageScale')
                    ->get()
                    ->pluck('performance.message.messageScale');
            }])
            ->where([
                'enrollment_id' => $enrollment->id,
                'periods_id' => $period_id,
                'asignatures_id' => $asignature_id
            ])
            ->get()
            ->pluck('notes')
            ->collapse();

        $response = array();

        foreach ($notes as $key => $note) {

            foreach ($note->noteParameter->notePerformances as $keyNP => $notePerformances) {
                try {
                    $message = $notePerformances->performance->message;
                    $scale = $this->getScaleByNote($note->value);

                    $messageScale = null;
                    $messageScale = (strtolower($scale->abbreviation) == 's') ? $message : $message->messageScale()->where('scale_evaluations_id', '=', $scale->id)->first();
                    $messageScale = (!is_null($messageScale)) ? $messageScale : $message;
                    array_push($response, $messageScale);
                } catch (\Exception $e) {

                }
            }
        }

        return $response;
    }

    public function resolvePerformancesCustom($asignature_id, $period_id, Enrollment $enrollment, $pensum_id, $notes = array())
    {

    }

    private function resolveIndicators($asignature_id, $period_id, Enrollment $enrollment, $pensum_id, $noteAsig)
    {
        $notes = EvaluationPeriod::with([
            'notes.noteParameter.notePerformances' => function ($pensum) use ($pensum_id, $period_id) {
                $pensum->where([
                    ['group_pensum_id', '=', $pensum_id],
                    ['periods_id', '=', $period_id]
                ])
                    ->with('performance.message.messageScale')
                    ->get()
                    ->pluck('performance.message.messageScale');
            }])
            ->where([
                'enrollment_id' => $enrollment->id,
                'periods_id' => $period_id,
                'asignatures_id' => $asignature_id
            ])
            ->get()
            ->pluck('notes')
            ->collapse();
        $response = array();

        foreach ($notes as $key => $note) {

            foreach ($note->noteParameter->notePerformances as $keyNP => $notePerformances) {
                try {

                    $message = $notePerformances->performance->message;
                    $scale = $this->getScaleByNote($noteAsig);

                    $messageScale = null;
                    $messageScale = (strtolower($scale->abbreviation) == 's') ? $message : $message->messageScale()->where('scale_evaluations_id', '=', $scale->id)->first();
                    $messageScale = (!is_null($messageScale)) ? $messageScale : $message;
                    array_push($response, $messageScale);

                } catch (\Exception $e) {
                }
            }
        }

        return $response;
    }

    private function resolveIndicatorsCustom($asignature_id, $period_id, $pensum_id, $noteAsig, $valoration = null)
    {

        $pPerformances = GroupPensum::with('performances.performance.message')
            ->where([
                ['id', $pensum_id],
                ['asignatures_id', $asignature_id]
            ])
            ->get()
            ->pluck('performances')
            ->collapse();

        $response = array();

        foreach ($pPerformances as $key => $performance) {
            if ($performance->periods_id == $period_id) {
                if (!is_null($valoration)) {
                    $message = $performance->performance->message;
                    $message->name = $valoration->wordExpresion->name . ", " . $message->name;

                    array_push($response, $message);
                }
            }
        }

        return $response;
    }

    //

    private function getScaleByNote($note)
    {
        foreach ($this->scaleEvaluation as $key => $scale) {

            if ($note >= $scale->rank_start && $note <= $scale->rank_end) {
                return $scale;
            }
        }
    }

    // Funciones Públicas

    public function averageStudents($arryStudentAverage, $enrollment)
    {

        try {
            #
            $count = 0;

            #Array donde se va almacenar objetos de estudiantes de arrayStudentAverage, pero con una estructra un poco modificada
            $vectorOfStudents = array();

            #En este vector se va a guardar el número de asignaras evaluada por cada estudiante
            $vectorNumberAsignatures = array();

            foreach ($arryStudentAverage as $key => $value) {
                $vectorStudent = array(
                    'id' => $value['enrollment_id'],
                    'average' => $value['average'],
                    'tav' => $value['tav']
                );

                #Se guarda la nueva estructura en el vector por cada estudiante
                $vectorOfStudents[$count] = $vectorStudent;

                # Se guarda el tav de asignatura del estudiante i o count..
                $vectorNumberAsignatures[$count] = $value['tav'];

                $count++;
            }

            #Obtengo y almaceno el número maximo de asignaturas evaluadas
            $numberMaxOfAsignatures = $this->getMaxValue($vectorNumberAsignatures);

            #Este es un nuevo vector donde se va a guardar los mismo estudiantes pero con el promedio levemente modificado
            $vectorOfStudentsAux = array();
            foreach ($vectorOfStudents as $value) {

                #Esta formula da como resultado un promedio auxiliar,
                #Nos soluciona el problema de aquellos estudiantes que tienen un promedio alto pero con menor
                #asignaturas evaluadas
                $averageAux = (($value['average'] * $value['tav']) / $numberMaxOfAsignatures);

                $vectorStudent = array(
                    'id' => $value['id'],
                    'averageAux' => $averageAux,
                    'average' => $value['average'],
                    'tav' => $value['tav']
                );
                #usamos el id de estudiante como el indice del vector
                $vectorOfStudentsAux[$value['id']] = $vectorStudent;

            }


            $vectorOfStudentsAux = $this->orderMultiDimensionalArray($vectorOfStudentsAux, 'averageAux', true);
            return $this->generateRating($vectorOfStudentsAux)[$enrollment->id];

        } catch (\Exception $e) {

        }

        // $vectorOfStudentsAux = self::orderMultiDimensionalArray($vectorOfStudentsAux, 'averageAux', true);
        // return self::generateRating($vectorOfStudentsAux);
    }

    public function orderMultiDimensionalArray($toOrderArray, $field, $inverse = false)
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

    public function generateRating($vectorOfStudentsAux)
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

    public function determineGradeBook()
    {
        foreach ($this->noteBook['periods'] as $periodKey => $period) {
            if ($period['periods_id'] == $this->noteBook['current_period']->periods_id) {
                foreach ($period['areas'] as $key => $area) {
                    if ($area['note'] > 0)
                        return true;
                }
            }
        }

        return false;
    }


    public function getMaxValue($array = array())
    {
        $max = 0;

        foreach ($array as $key => $value) {

            if ($value > $max)
                $max = $value;
        }

        return $max;
    }
}

