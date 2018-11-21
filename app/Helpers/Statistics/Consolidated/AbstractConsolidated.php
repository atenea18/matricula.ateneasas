<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 31/07/2018
 * Time: 5:40 PM
 */

namespace App\Helpers\Statistics\Consolidated;

use App\Helpers\Statistics\ParamsStatistics;
use App\Helpers\Utils\GenerateRating;

abstract class AbstractConsolidated
{
    protected $group_object = null;
    protected $institution_object = null;
    protected $minimum_scale_object = null;

    protected $middle_point = 0;
    protected $final_point = 0;
    protected $num_of_periods = 0;
    protected $period_selected_id = 1;


    protected $vectorNotes = [];
    protected $vectorPeriods = [];
    protected $vectorSubjects = [];
    protected $vectorEnrollments = [];
    protected $report_asignatures = [];

    public function __construct(ParamsStatistics $params)
    {

        /*
         * Variables
         */
        $this->middle_point = $params->middle_point;
        $this->final_point = $params->final_point;
        $this->num_of_periods = $params->num_of_periods;
        $this->period_selected_id = $params->period_selected_id;

        /*
         * Objetos
         */
        $this->group_object = $params->group_object;
        $this->institution_object = $params->institution_object;
        $this->minimum_scale_object = $params->minimum_scale_object;

        /*
         * Arreglos
         */
        $this->vectorNotes = $params->vectorNotes;
        $this->vectorPeriods = $params->vectorPeriods;
        $this->vectorSubjects = $params->vectorSubjects;
        $this->vectorEnrollments = $params->vectorEnrollments;
        $this->report_asignatures = $params->report_asignatures;
    }

    protected function getProcessedRequest()
    {
        $this->processVectorEnrollment();
    }

    /**
     * Construye un vector con toda la información necesaria para el consolidado
     */
    private function processVectorEnrollment()
    {
        foreach ($this->vectorPeriods as $period) {
            $this->addPropertyToEnrollmentPeriodsEvaluated($period);
        }


        $this->addPropertiesToEnrollments();
        $this->addPropertyToEnrollmentsAccumulatedAsignatures();
        $this->addPropertyToEnrollmentsRequiredValuation();
        $this->addPropertyToEnrollmentsFinalReport();
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
    private function addPropertiesToEnrollments()
    {
        $vectorDataBasicPeriodsEvaluated = $this->createVectorDataPeriodsEvaluated();

        foreach ($vectorDataBasicPeriodsEvaluated as $rowDataBasicPeriodEvaluated) {
            $this->createProperties($rowDataBasicPeriodEvaluated);
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
                        'student_name' => $enrollment->student_name,
                        'average' => $enrollPeriodEvaluated['average'],
                        'student_last_name' => $enrollment->student_last_name,
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
     * Recorre cada estudiante para asignarle dos propiedades:
     * enrollment.accumulatedAverage
     * enrollment.evaluatedPeriods.rating
     */
    private function createProperties($rowDataBasicPeriodEvaluated)
    {
        $vectorRating = GenerateRating::createVectorRating($rowDataBasicPeriodEvaluated->enrollments);

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
        if (!isset($enrollment->average)) {
            $enrollment->average = 0;
        }
        $enrollment->average += ($enrollPeriodEvaluated['average'] * ($rowDataBasicPeriodEvaluated->percent / 100));
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
            if (!isset($enrollment->tav)) {
                $enrollment->tav = 0;
            }
            $enrollment->accumulatedSubjects = $accumulated;
            $enrollment->tav = count($accumulated);
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
                        if ($note->value > 0) {
                            $info = (object)array(
                                'period_id' => $rowEvaluatedPeriod['period_id'],
                                'percent' => $rowEvaluatedPeriod['percent']
                            );
                            array_push($periodsEvaluated, $info);
                            $sum += $this->calculateAccumulatedNotes($note, $rowEvaluatedPeriod, $countTavAsignatures);
                        }
                    }
                }
            }
            $data = (object)array(
                'average' => round($sum, 1),
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
            $valueRequired = 1000;


            foreach ($enrollment->accumulatedSubjects as $rowAccumulated) {
                if ($subject->asignatures_id == $rowAccumulated->asignatures_id) {

                    $rest = $this->middle_point - $rowAccumulated->average;
                    $missingPeriod = $this->num_of_periods - count($rowAccumulated->periods);
                    $divide = 0;

                    if ($missingPeriod > 0 && $missingPeriod < $this->num_of_periods) {
                        $divide = $rest / $missingPeriod;
                        $index = count($rowAccumulated->periods);

                        $percent = $this->vectorPeriods[$index]->percent / 100;
                        $valueRequired = $divide / $percent;
                    }

                    if ($missingPeriod == 0) {
                        if ($rowAccumulated->average >= $this->middle_point)
                            $valueRequired = -1000;
                    }


                }

            }

            $data = (object)array(
                'required' => round($valueRequired, 1),
                'name' => $subject->name,
                'asignatures_id' => $subject->asignatures_id,
            );

            array_push($required, $data);
        }
    }


    private function addPropertyToEnrollmentsFinalReport()
    {
        $vectorRating = GenerateRating::createVectorRating($this->vectorEnrollments);

        foreach ($this->vectorEnrollments as &$enrollment) {
            $this->calculateRatingGeneral($enrollment, $vectorRating);

            $report = [];
            $failed_subjcts = [];

            $this->calculateFinalReport($enrollment, $report, $failed_subjcts);

            if (!isset($enrollment->finalReport)) {
                $enrollment->finalReport = [];
            }
            if (!isset($enrollment->failedSubjects)) {
                $enrollment->failedSubjects = [];
            }
            $enrollment->finalReport = $report;
            $enrollment->failedSubjects = (object)array(
                'number' => count($failed_subjcts),
                'subjects' => $failed_subjcts,
            );
        }
    }

    private function calculateRatingGeneral(&$enrollment, $vectorRating)
    {
        foreach ($vectorRating as $rowEnrollmentRating) {

            if ($enrollment->id == $rowEnrollmentRating['id']) {
                $enrollment->rating = $rowEnrollmentRating['rating'];
            }
        }
    }

    private function calculateFinalReport($enrollment, &$report, &$failed_subjcts)
    {

        $result = null;
        $overcoming = 0;
        $value = "";
        $failed = null;

        foreach ($this->vectorSubjects as $subject) {

            foreach ($this->report_asignatures as $report_asignature) {
                if ($enrollment->id == $report_asignature->enrollment_id && $report_asignature->asignatures_id == $subject->asignatures_id) {
                    if ($report_asignature->value < $this->middle_point) {
                        $result = "REP";
                        $failed = (object)array(
                            'asignatures_id' => $report_asignature->asignatures_id,
                            'average' => $report_asignature->value,
                            'overcoming' => $report_asignature->overcoming,
                            'name' => $report_asignature->name,
                        );
                        $overcoming = $report_asignature->overcoming;

                        if ($report_asignature->overcoming >= $this->middle_point) {
                            $failed = null;
                            $result = "APR";
                        }

                        if ($failed != null)
                            array_push($failed_subjcts, $failed);

                    } else {
                        $result = "APR";
                    }
                }
            }

            foreach ($enrollment->accumulatedSubjects as $accumulated) {
                if ($accumulated->asignatures_id == $subject->asignatures_id) {
                    $data = (object)array(
                        'name' => $accumulated->name,
                        'asignatures_id' => $accumulated->asignatures_id,
                        'value' => $accumulated->average,
                        'overcoming' => $overcoming,
                        'report' => $result,
                    );

                    if($result == null ){
                        if($accumulated->average < $this->middle_point)
                            $data->report = "REP";
                        else
                            $data->report = "APR";
                    }

                    if ($data != null)
                        array_push($report, $data);
                }
            }
        }
    }

    /*
     *  Métodos Auxiliares // Métodos Auxiliares \\ Métodos Auxiliares
     */

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


}