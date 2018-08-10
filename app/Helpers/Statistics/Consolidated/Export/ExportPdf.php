<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 7/08/2018
 * Time: 9:51 PM
 */

namespace App\Helpers\Statistics\Consolidated\Export;


use App\Helpers\Statistics\ParamsStatistics;
use App\Traits\utf8Helper;
use setasign\Fpdi\Fpdi;


class ExportPdf extends Fpdi
{
    use utf8Helper;

    private $params = null;
    private $page_width = null;
    private $size_column = null;
    private $size_column_fixed = null;
    private $enrollments_by_groups = [];
    private $num_is_accumulated = 2;

    // Se define el width de cada celda
    private $w_num = 5;
    private $w_per = 6;
    private $w_tav = 7;
    private $w_pgg = 8;
    private $w_rat = 10;
    private $w_name = 47;
    private $w_margin = 20;

    // Se define el height de todas las celdas
    private $h_cell = 3.109;

    private $enrollmentByGroup = null;

    public function __construct(string $orientation = 'Landscape', string $size = 'Letter', $enrollments_by_groups, ParamsStatistics $params)
    {
        $this->params = $params;
        $this->enrollments_by_groups = $enrollments_by_groups;

        $this->getSizeMaxColumnFixed();
        parent::__construct($orientation, 'mm', $size);

    }


    public function Header()
    {
        if ($this->enrollmentByGroup) {
            $this->SetLineWidth(0.001);
            $this->SetDrawColor(76, 76, 76);

            $this->TitleInstitutionHeader();
            $this->TitleInformationGroupHeader();

            $this->HeaderTableConsolidated($this->enrollmentByGroup);
        }
    }


    private function TitleInstitutionHeader()
    {
        $this->SetY(4);
        // Select Arial bold 15
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($this->page_width - $this->w_margin, 5.6, $this->transformMay($this->params->institution_object->name), 'LTR', 1, 'C');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->page_width - $this->w_margin, 4, $this->transformMay($this->enrollmentByGroup->headquarter_name), 'LR', 1, 'C');
        $this->Cell($this->page_width - $this->w_margin, 4, $this->transformMay('Consolidado'), 'LR', 1, 'C');
        $this->Cell($this->page_width - $this->w_margin, 0, '', 'T', 1);
    }

    private function TitleInformationGroupHeader()
    {
        $this->SetFillColor(228, 229, 230);
        $this->SetFont('Arial', '', 7);
        $this->Cell(45, 4, $this->transformMay('Grupo: ' . $this->enrollmentByGroup->name), 'L', 0, 'C', true);
        $this->Cell(50, 4, $this->transformMay('Jornada: ' . $this->enrollmentByGroup->working_day_name), '', 0, 'C', true);
        $this->Cell(110, 4, $this->transformMay('Director: ' . $this->enrollmentByGroup->director_name), '', 0, 'C', true);
        $this->Cell(54.4, 4, $this->transformMay('Fecha: ' . date("Y-m-d")), 'R', 1, 'C', true);
    }

    private function HeaderTableConsolidated($enrollmentByGroup)
    {
        $this->SetFillColor(208, 233, 251);
        $this->SetFont('Arial', 'B', 6.6);
        $this->columnHeaderTitles($enrollmentByGroup);

    }

    private function columnHeaderTitles($enrollmentByGroup)
    {
        $this->Cell($this->w_num, $this->h_cell, 'No.', 1, 0, 'C', true);
        $this->Cell($this->w_name, $this->h_cell, 'APELLIDOS Y NOMBRES', 1, 0, 'C', true);
        $this->Cell($this->w_per, $this->h_cell, 'PER', 1, 0, 'C', true);
        $this->Cell($this->w_tav, $this->h_cell, 'TAV', 1, 0, 'C', true);
        $this->Cell($this->w_rat, $this->h_cell, 'PUESTO', 1, 0, 'C', true);
        $this->Cell($this->w_pgg, $this->h_cell, 'PGG', 1, 0, 'C', true);

        //Encabezado de asignaturas
        foreach ($enrollmentByGroup->subjects as $subject) {
            $this->Cell($this->size_column, $this->h_cell, utf8_decode($subject->abbreviation), 1, 0, 'C', true);
        }
        $this->ln();
    }


    public function create($name_pdf)
    {
        foreach ($this->enrollments_by_groups as $enrollmentByGroup) {
            if (count($enrollmentByGroup->subjects))
                $this->BodyTableConsolidated($enrollmentByGroup);
        }
        $this->Output('D', $name_pdf . '.pdf', true);
    }

    private function BodyTableConsolidated($enrollmentByGroup)
    {
        $this->setConfig($enrollmentByGroup);

        foreach ($enrollmentByGroup->enrollments as $key => $enrollment) {
            $this->columnsBodyTableConsolidated($enrollment, $enrollmentByGroup, $key + 1);
        }
    }

    private function setConfig($enrollmentByGroup)
    {
        $this->enrollmentByGroup = $enrollmentByGroup;
        $this->page_width = $this->GetPageWidth();
        $this->size_column = $this->getSizeColumn($enrollmentByGroup);

        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(76, 76, 76);
        $this->SetFont('Arial', '', 7.4);

        $this->AddPage();
    }

    private function columnsBodyTableConsolidated($enrollment, $enrollmentByGroup, $count)
    {
        $this->Cell($this->w_num, $this->getHeightColumnNum(), $count, 1, 0, 'C');
        $this->CellEnrollmentName($enrollment);
        $this->ColumnsValoration($enrollment, $enrollmentByGroup);
        $this->RowsPeriodsAccumulated($enrollment, $enrollmentByGroup);
    }

    private function RowsPeriodsAccumulated($enrollment, $enrollmentByGroup)
    {
        if ($this->params->is_accumulated == "true") {
            //Accumulated
            $average = $enrollment->accumulatedAverage;
            $this->SetFillColor(247, 251, 254);
            $this->SetX(15);
            $this->Cell(70, $this->h_cell, $this->transformMay('Acumulados'), 1, 0, 'L', true);
            $this->Cell(8, $this->h_cell, ROUND($average, 2), 1, 0, 'L', true);
            $this->searchAccumulatedNotes($enrollment, $enrollmentByGroup);
            //requiredValuation
            $this->SetX(15);
            $this->Cell(78, $this->h_cell, $this->transformMay('Valoración Requerida'), 1, 0, 'L', true);
            $this->searchRequiredNotes($enrollment, $enrollmentByGroup);
        }
    }

    private function ColumnsValoration($enrollment, $enrollmentByGroup)
    {
        foreach ($enrollment->evaluatedPeriods as $rowEvaluatePeriod) {
            if ($this->params->is_accumulated == "false") {
                if ($rowEvaluatePeriod['period_id'] == $this->params->period_selected_id)
                    $this->getRowValoration($enrollmentByGroup, $rowEvaluatePeriod);
            } else {
                $this->SetX(62);
                $this->getRowValoration($enrollmentByGroup, $rowEvaluatePeriod);
            }
        }
    }

    private function getRowValoration($enrollmentByGroup, $rowEvaluatePeriod)
    {
        $this->Cell($this->w_per, $this->h_cell, $rowEvaluatePeriod['period_id'], 1, 0, 'C');
        $this->Cell($this->w_tav, $this->h_cell, $rowEvaluatePeriod['tav'], 1, 0, 'C');
        $this->Cell($this->w_rat, $this->h_cell, $rowEvaluatePeriod['rating'], 1, 0, 'C');
        $this->Cell($this->w_pgg, $this->h_cell, $rowEvaluatePeriod['average'], 1, 0, 'C');
        $this->searchNotes($enrollmentByGroup, $rowEvaluatePeriod['notes']);
    }

    private function searchNotes($enrollmentByGroup, $notes)
    {
        foreach ($enrollmentByGroup->subjects as $subject) {
            $state = false;
            foreach ($notes as $note) {
                if ($subject->asignatures_id == $note->asignatures_id) {
                    $value = self::processNote($note->value, $note->overcoming);
                    $this->setDanger($value, $this->params->middle_point);
                    $this->Cell($this->size_column, $this->h_cell, ROUND($value, 1), 1, 0, 'C');
                    $this->setTextBlack();
                    $state = true;
                }
            }
            if (!$state)
                $this->Cell($this->size_column, $this->h_cell, '', 1, 0, 'C');
        }
        $this->ln();
    }

    private function getHeightColumnNum()
    {
        if ($this->params->is_accumulated == "true")
            return $this->h_cell * ($this->params->num_of_periods + $this->num_is_accumulated);
        else
            return $this->h_cell;
    }

    private function getHeightColumnName()
    {
        if ($this->params->is_accumulated == "true")
            return $this->h_cell * $this->params->num_of_periods;
        else
            return $this->h_cell;
    }

    private function searchAccumulatedNotes($enrollment, $enrollmentByGroup)
    {
        foreach ($enrollmentByGroup->subjects as $key => $subject) {
            $state = false;
            foreach ($enrollment->accumulatedSubjects as $accumulated) {
                if ($subject->asignatures_id == $accumulated->asignatures_id) {
                    $value = self::processNote($accumulated->average, 0);
                    $this->setDanger($value, $this->params->middle_point);
                    $this->Cell($this->size_column, $this->h_cell, ROUND($value, 2), 1, 0, 'C', true);
                    $this->setTextBlack();
                    $state = true;
                }
            }
            if (!$state)
                $this->Cell($this->size_column, $this->h_cell, '', 1, 0, 'C', true);

        }
        $this->ln();
    }

    private function searchRequiredNotes($enrollment, $enrollmentByGroup)
    {
        foreach ($enrollmentByGroup->subjects as $key => $subject) {
            $state = false;
            foreach ($enrollment->requiredValuation as $required) {
                if ($subject->asignatures_id == $required->asignatures_id) {
                    $value = self::processNote($required->required, 0);
                    $this->Cell($this->size_column, $this->h_cell, ROUND($value, 2), 1, 0, 'C', true);
                    $state = true;
                }
            }
            if (!$state)
                $this->Cell($this->size_column, $this->h_cell, '', 1, 0, 'C', true);

        }
        $this->ln();
    }

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

    private function CellEnrollmentName($enrollment)
    {
        $this->Cell($this->w_name, $this->getHeightColumnName(), self::transformFullName($enrollment), 1, 0, 'L');
    }

    private static function transformFullName($enrollment)
    {
        return substr(utf8_decode($enrollment->student_last_name . ' ' . $enrollment->student_name), 0, 29);
    }

    private function getSizeColumn($enrollmentByGroup)
    {
        if (count($enrollmentByGroup->subjects))
            return ($this->page_width - $this->size_column_fixed) / count($enrollmentByGroup->subjects);
        else
            return ($this->page_width - $this->size_column_fixed);
    }

    private function getSizeMaxColumnFixed()
    {
        $this->size_column_fixed =
            $this->w_num + $this->w_per + $this->w_tav + $this->w_pgg + $this->w_rat + $this->w_name + $this->w_margin;
    }

    private function transformMay($string)
    {
        return strtoupper($this->hideTilde($string));
    }

    public function Footer()
    {
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Print centered page number
        $this->Cell(0, 4, utf8_decode('Atenea - Página ' . $this->PageNo()), 0, 0, 'C');
    }


}