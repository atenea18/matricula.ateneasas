<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 7/08/2018
 * Time: 9:51 PM
 */

namespace App\Helpers\Statistics\Consolidated\Export;


use App\Helpers\Statistics\Consolidated\AbstractPDF;
use App\Helpers\Statistics\ParamsStatistics;
use App\Traits\utf8Helper;
use setasign\Fpdi\Fpdi;


class ExportPdf extends AbstractPDF
{
    use utf8Helper;
    private $enrollments_by_groups = null;
    private $table_consolidated = [
        'rows_pan' => 1,
        'rows_pan_accumulated' => 2
    ];

    private static $number = 0;

    public function __construct(string $orientation = 'Landscape', string $size = 'Letter', $enrollments_by_groups, ParamsStatistics $params)
    {
        parent::__construct($orientation, $size);
        $this->start($enrollments_by_groups, $params);
    }

    private function start($enrollments_by_groups, ParamsStatistics $params)
    {
        $this->params = $params;
        $this->enrollments_by_groups = $enrollments_by_groups;
    }

    protected function drawTableTitlesDynamic()
    {
        if($this->params->is_report != "true" && $this->params->is_filter_report != "true" )
            $this->consolidatedTitle();
        if($this->params->is_report == "true")
            $this->reportFinalTitle();
        if($this->params->is_filter_report == "true" )
            $this->reportFilterTitle();
    }

    public function create($name)
    {
        foreach ($this->enrollments_by_groups as $enrollmentByGroup) {
            $this->enrollmentByGroup = $enrollmentByGroup;

            if($this->params->is_report != "true" && $this->params->is_filter_report != "true" )
                $this->consolidatedBody();
            if($this->params->is_report == "true")
                $this->reportFinalBody();
            if($this->params->is_filter_report == "true" )
                $this->reportFilterBody();

        }
        $this->Output('D', $name . '.pdf', true);
    }


    # CONSOLIDATED *********************************** CONSOLIDATED ******************************
    protected function initConsolidated()
    {
        $cells = array(
            (object)array('title' => 'No.', 'width' => 5),
            (object)array('title' => 'Nombres y Apellidos.', 'width' => 47),
            (object)array('title' => 'PER.', 'width' => 7),
            (object)array('title' => 'TAV.', 'width' => 8),
            (object)array('title' => 'PUESTO.', 'width' => 10),
            (object)array('title' => 'PGG.', 'width' => 8),
        );
        $this->setTableCells($cells);
    }

    # Table Consolidated Title
    private function consolidatedTitle()
    {
        $this->setNumberDynamicTitles(count($this->enrollmentByGroup->subjects));
        //Encabezado de asignaturas
        foreach ($this->enrollmentByGroup->subjects as $subject) {
            $this->drawCellWithDynamic($subject->abbreviation);
        }
        $this->ln();
    }

    # Table Consolidated Body
    private function consolidatedBody()
    {
        $this->initConsolidated();
        $this->setStatisticalTitle("CONSOLIDADOS", "CONSOLIDADOS CON PERIODOS ACUMULADOS");
        if (count($this->enrollmentByGroup->subjects)) {
            $this->setConfig();
            $this->consolidatedContent();
        }
    }

    private function consolidatedContent()
    {
        self::$number = $this->table_consolidated['rows_pan_accumulated'];

        foreach ($this->enrollmentByGroup->enrollments as $key => $enrollment) {
            $this->drawCellWithIndex($key + 1, 0, $this->getRowNumber(self::$number));
            $this->drawCellWithIndex(self::fullNameEnrollment($enrollment), 1, $this->getRowNumber(0));
            $this->dispatcherConsolidated($enrollment);
            $this->rowsAccRequ($enrollment);
        }
    }

    # CHOOSE BETWEEN ACCUMULATED OR NOT
    private function dispatcherConsolidated($enrollment)
    {
        foreach ($enrollment->evaluatedPeriods as $rowEvaluation) {
            switch ($this->params->is_accumulated) {
                case "false":
                    if ($rowEvaluation['period_id'] == $this->params->period_selected_id)
                        $this->getOtherColumnsConsolidated($rowEvaluation);
                    break;
                default:
                    $this->SetX(62);
                    $this->getOtherColumnsConsolidated($rowEvaluation);
            }
        }
    }

    private function rowsAccRequ($enrollment)
    {
        if ($this->params->is_accumulated == "true") {
            $average = $enrollment->accumulatedAverage;
            $this->SetFillColor(247, 251, 254);
            $this->SetX(15);
            $this->drawCell('Acumulados', 54);
            $this->drawCellWithIndex($enrollment->tav, 3);
            $this->drawCellWithIndex($enrollment->rating, 4);
            $this->drawCell(ROUND($average, 1),8);
            $this->searchAccumulatedNotes($enrollment);

            //requiredValuation
            $this->SetX(15);
            $this->drawCell('Valoración Requerida',80);
            $this->searchRequiredNotes($enrollment);
        }
    }

    private function getOtherColumnsConsolidated($rowEvaluation)
    {
        $this->drawCellWithIndex($rowEvaluation['period_id'], 2);
        $this->drawCellWithIndex($rowEvaluation['tav'], 3);
        $this->drawCellWithIndex($rowEvaluation['rating'], 4);
        $this->drawCellWithIndex($rowEvaluation['average'], 5);
        $this->searchNotes($rowEvaluation['notes']);

    }

    private function searchNotes($notes)
    {
        foreach ($this->enrollmentByGroup->subjects as $subject) {
            $state = false;
            foreach ($notes as $note) {
                if ($subject->asignatures_id == $note->asignatures_id) {
                    $value = self::processNote($note->value, $note->overcoming);
                    $this->setDanger($value, $this->params->middle_point);
                    if ($this->params->is_reprobated == "true") {
                        if ($value < $this->params->middle_point && $value > 0)
                            $this->drawCellWithDynamic(ROUND($value, 1));
                        else
                            $this->drawCellWithDynamic('');
                    } else {
                        $this->drawCellWithDynamic(ROUND($value, 1));
                    }
                    $this->setTextBlack();
                    $state = true;
                }
            }
            if (!$state)
                $this->drawCellWithDynamic('');
        }
        $this->ln();
    }

    private function searchAccumulatedNotes($enrollment)
    {
        foreach ($this->enrollmentByGroup->subjects as $key => $subject) {
            $state = false;
            foreach ($enrollment->accumulatedSubjects as $accumulated) {
                if ($subject->asignatures_id == $accumulated->asignatures_id) {
                    $value = self::processNote($accumulated->average, 0);
                    $this->setDanger($value, $this->params->middle_point);
                    $valueCell = ROUND($value, 1) == 0 ? '' : ROUND($value, 1);
                    $this->drawCellWithDynamic($valueCell);
                    $this->setTextBlack();
                    $state = true;
                }
            }
            if (!$state)
                $this->drawCellWithDynamic('');

        }
        $this->ln();
    }

    private function searchRequiredNotes($enrollment)
    {
        foreach ($this->enrollmentByGroup->subjects as $key => $subject) {
            $state = false;
            foreach ($enrollment->requiredValuation as $required) {
                if ($subject->asignatures_id == $required->asignatures_id) {
                    $value = self::processNote($required->required, 0);
                    $valueCell = ROUND($value, 1) == 0 ? '' : ROUND($value, 1);
                    $this->drawCellWithDynamic($valueCell);
                    $state = true;
                }
            }
            if (!$state)
                $this->drawCellWithDynamic('');

        }
        $this->ln();
    }

    # HELPERS *********************
    private static function fullNameEnrollment($enrollment)
    {
        return substr($enrollment->student_last_name . ' ' . $enrollment->student_name, 0, 29);
    }

    private function getRowNumber($number)
    {
        //
        if ($this->params->is_accumulated == "true")
            return $this->params->num_of_periods + $number;
        else
            return 1;
    } // END CONSOLIDATED -----------------------------------------------------






    # REPORT FINAL *********************************** REPORT FINAL ******************************
    protected function initReportFinal()
    {
        $cells = array(
            (object)array('title' => 'No.', 'width' => 5),
            (object)array('title' => 'Nombres y Apellidos.', 'width' => 47),
            (object)array('title' => 'TAV.', 'width' => 8),
            (object)array('title' => 'PUESTO.', 'width' => 10),
            (object)array('title' => 'PGG.', 'width' => 8),
        );
        $this->setTableCells($cells);
    }

    private function reportFinalTitle()
    {
        $this->setNumberDynamicTitles(count($this->enrollmentByGroup->subjects));
        //Encabezado de asignaturas
        foreach ($this->enrollmentByGroup->subjects as $subject) {
            $this->drawCellWithDynamic($subject->abbreviation);
        }
        $this->ln();
    }

    # Table REPORT FINAL BODY
    private function reportFinalBody()
    {
        $this->initReportFinal();
        $this->setStatisticalTitle("REPORTE FINAL", "");
        if (count($this->enrollmentByGroup->subjects)) {
            $this->setConfig();
            $this->reportFinalContent();
        }
    }

    private function reportFinalContent()
    {
        self::$number = 2;

        foreach ($this->enrollmentByGroup->enrollments as $key => $enrollment) {
            $this->drawCellWithIndex($key + 1, 0, self::$number);
            $this->drawCellWithIndex(self::fullNameEnrollment($enrollment), 1, self::$number);
            $this->drawCellWithIndex($enrollment->tav, 2, self::$number);
            $this->drawCellWithIndex($enrollment->rating, 3, self::$number);
            $average = $enrollment->accumulatedAverage;
            $this->drawCellWithIndex(ROUND($average, 1),4, self::$number);
            $this->searchAccumulatedNotes($enrollment);
            $this->searchFinalReport($enrollment);


        }
    }

    private function searchFinalReport($enrollment)
    {
        $this->SetX(88);
        foreach ($this->enrollmentByGroup->subjects as $key => $subject) {
            $state = false;
            foreach ($enrollment->finalReport as $final) {
                if ($subject->asignatures_id == $final->asignatures_id) {
                    $value = self::processNote($final->value, 0);
                    $this->setDanger($value, $this->params->middle_point);
                    $this->drawCellWithDynamic($final->report);
                    $this->setTextBlack();
                    $state = true;
                }
            }
            if (!$state)
                $this->drawCellWithDynamic('');

        }
        $this->ln();
    }


    # HELPERS **********************************

    private static function processNote($note, $overcoming)
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

    private function setDanger($note, $limit)
    {
        if ($note < $limit) {
            $this->SetTextColor(255, 0, 0);
        } else {
            $this->SetTextColor(0, 0, 0);
        }
    }

    private function setTextBlack()
    {
        $this->SetTextColor(0, 0, 0);

    }








    # REPORT FILTER *********************************** REPORT FILTER ******************************
    protected function initReportFilter()
    {
        $cells = array(
            (object)array('title' => 'No.', 'width' => 5),
            (object)array('title' => 'Nombres y Apellidos.', 'width' => 56),
            (object)array('title' => 'TAV.', 'width' => 14),
            (object)array('title' => 'PUESTO.', 'width' => 14),
            (object)array('title' => 'PGG.', 'width' => 14),
            (object)array('title' => 'ASIGNATURAS.', 'width' => 130),
        );
        $this->setTableCells($cells);
    }

    private function reportFilterTitle()
    {
        $this->setNumberDynamicTitles(1);
        $this->drawCellWithDynamic("VALORACIÓN");
        $this->ln();
    }

    private function reportFilterBody(){
        $this->initReportFilter();
        $result= ['0'=>'IGUAL A', '1' => 'MYOR/IGUAL A', '2' => 'MEN/IGUAL A'];
        $condition = $result[$this->params->condition];
        $condition_number = $this->params->condition_number;

        $this->setStatisticalTitle("INFORME FINAL REPROBADOS | ".$condition." ".$condition_number, "");
        if (count($this->enrollmentByGroup->subjects)) {
            $this->setConfig();
            $this->reportFilterContent();
        }
    }

    private function reportFilterContent()
    {


        foreach ($this->enrollmentByGroup->enrollments as $key => $enrollment) {

            switch ($this->params->condition){
                case "0":
                    if($enrollment->failedSubjects->number == $this->params->condition_number)
                        $this->contentFiltered($enrollment, $key);
                    break;
                case "1":
                    if($enrollment->failedSubjects->number >= $this->params->condition_number)
                        $this->contentFiltered($enrollment, $key);
                    break;
                default:
                    if($enrollment->failedSubjects->number <= $this->params->condition_number && $enrollment->failedSubjects->number >0 )
                        $this->contentFiltered($enrollment, $key);
            }
        }
    }

    private function contentFiltered($enrollment, $key){
        self::$number = $enrollment->failedSubjects->number;
        $this->drawCellWithIndex($key + 1, 0, self::$number);
        $this->drawCellWithIndex(self::fullNameEnrollment($enrollment), 1, self::$number);
        $this->drawCellWithIndex($enrollment->failedSubjects->number, 2, self::$number);
        $this->drawCellWithIndex($enrollment->rating, 3, self::$number);
        $average = $enrollment->accumulatedAverage;
        $this->drawCellWithIndex(ROUND($average, 1),4, self::$number);
        foreach ($enrollment->failedSubjects->subjects as $key_ => $subject){
            if($key_ != 0){
                $this->SetX(113);
            }
            $this->drawCellWithIndex($subject->name,5);
            $this->drawCellWithDynamic(ROUND($subject->average, 1));
            $this->ln();
        }
    }
    
}