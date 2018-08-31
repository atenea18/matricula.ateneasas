<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 27/08/2018
 * Time: 8:20 PM
 */

namespace App\Helpers\Statistics\Percentage;

use App\Helpers\Statistics\Consolidated\AbstractConsolidated;
use App\Helpers\Statistics\ParamsStatistics;

abstract class AbstractPercentage extends AbstractConsolidated
{
    private $vectorScales = null;

    public function __construct(ParamsStatistics $params)
    {
        parent::__construct($params);
        $this->vectorScales = $params->vectorScales;
    }

    public function getProcessedRequest()
    {
        parent::getProcessedRequest();


        foreach ($this->vectorSubjects as &$subjects) {
            $vectorPeriods = [];

            foreach ($this->vectorPeriods as $period) {
                $vectorScales = [];
                foreach ($this->vectorScales as $scale) {
                    $scalex = (object)array(
                        'name' => $scale->name,
                        'abbreviation' => $scale->abbreviation,
                        'rank_start' => $scale->rank_start,
                        'rank_end' => $scale->rank_end,
                        'counter' => 0,
                        'percent_counter' => 0,
                    );
                    array_push($vectorScales, $scalex);
                }
                $periodx = (object)array(
                    'name' => $period->periods_name,
                    'period_id' => $period->periods_id,
                    'percent' => $period->percent,
                    'vectorScales' => $vectorScales
                );

                array_push($vectorPeriods, $periodx);
            }
            $subjects->vectorPeriods = $vectorPeriods;
        }

        $num_enrollment = count($this->vectorEnrollments);
        foreach ($this->vectorEnrollments as $enrollment) {

            foreach ($enrollment->evaluatedPeriods as $evaluatedPeriod) {
                foreach ($evaluatedPeriod['notes'] as $note) {
                    foreach ($this->vectorSubjects as $subj) {
                        if ($note->asignatures_id == $subj->asignatures_id) {
                            foreach ($subj->vectorPeriods as $pp) {
                                if ($evaluatedPeriod['period_id'] == $pp->period_id) {
                                    foreach ($pp->vectorScales as $scc) {
                                        if ($scc->rank_start <= $note->value && $scc->rank_end >= $note->value) {
                                            $scc->counter += 1;
                                            $scc->percent_counter += (1 / $num_enrollment) * 100;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->vectorSubjects;


    }

    private
    function getPercentageSubjects()
    {


    }

}