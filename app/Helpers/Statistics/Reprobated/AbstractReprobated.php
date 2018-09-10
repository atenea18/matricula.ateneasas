<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 8/09/2018
 * Time: 1:05 PM
 */

namespace App\Helpers\Statistics\Reprobated;


use App\Helpers\Statistics\Consolidated\AbstractConsolidated;
use App\Helpers\Statistics\ParamsStatistics;
use App\Helpers\Utils\Utils;

class AbstractReprobated extends AbstractConsolidated
{


    public function __construct(ParamsStatistics $params)
    {
        parent::__construct($params);

    }

    public function getProcessedRequest()
    {
        parent::getProcessedRequest();

        foreach ($this->vectorEnrollments as $enrollment) {
            foreach ($this->vectorPeriods as &$vectorPeriod) {
                foreach ($enrollment->evaluatedPeriods as $period) {
                    if ($vectorPeriod->periods_id == $period['period_id']) {
                        $asignatures = [];
                        foreach ($period['notes'] as $note) {
                            $value_note = Utils::process_note($note->value, $note->overcoming);
                            if ( $value_note > 0 && $value_note < $this->middle_point) {
                                array_push($asignatures, $note);
                            }
                        }

                    }
                }
                $enrollment_repro = (object)array(
                    'enrollment_id' => $enrollment->id,
                    'student_name' => $enrollment->student_name,
                    'student_last_name' => $enrollment->student_last_name,
                    'accumulatedAverage' => $enrollment->accumulatedAverage,
                    'accumulatedSubjects' => $enrollment->accumulatedSubjects,
                    'requiredValuation' => $enrollment->requiredValuation,
                    'asignatures' => $asignatures
                );
                if (count($asignatures) > 0) {
                    if (!isset($vectorPeriod->enrollments)) {
                        $vectorPeriod->enrollments = [];
                    }
                    array_push($vectorPeriod->enrollments, $enrollment_repro);
                }
            }

        }

        return $this->vectorPeriods;

    }
}