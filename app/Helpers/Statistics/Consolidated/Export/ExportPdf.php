<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 7/08/2018
 * Time: 9:51 PM
 */

namespace App\Helpers\Statistics\Consolidated\Export;


use App\Helpers\Statistics\ParamsStatistics;
use setasign\Fpdi\Fpdi;

class ExportPdf extends Fpdi
{
    private $params = null;
    private $page_width = null;
    private $size_column = null;
    private $size_column_fixed = null;
    private $enrollments_by_groups = [];

    // Se define el width de cada celda
    private $w_num = 5;
    private $w_per = 6;
    private $w_tav = 7;
    private $w_pgg = 7;
    private $w_rat = 10;
    private $w_name = 47;
    private $w_margin = 20;

    // Se define el height de todas las celdas
    private $h_cell = 4;

    private $vector = null;

    public function __construct(string $orientation = 'Landscape', string $size = 'Letter', $enrollments_by_groups, ParamsStatistics $params)
    {
        $this->params = $params;
        $this->enrollments_by_groups = $enrollments_by_groups;

        $this->getSizeMaxColumnFixed();
        parent::__construct($orientation, 'mm', $size);

    }

    public function Header()
    {
        $this->SetLineWidth(0.001);
        $this->SetDrawColor(76, 76, 76);

        $this->headingTitle();
        $this->headingSubTitle();

        $this->headerTable($this->vector);
    }

    /**
     * Institución, Sede
     */
    private function headingTitle()
    {
        // Select Arial bold 15
        $this->SetFont('Arial', 'B', 12);
        $this->Cell($this->page_width - $this->w_margin, 8, self::transformMay($this->params->institution_object->name), 'LTR', 1, 'C');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($this->page_width - $this->w_margin, 5, self::transformMay($this->vector->headquarter_name), 'LR', 1, 'C');
        $this->Cell($this->page_width - $this->w_margin, 5, self::transformMay('Consolidado'), 'LR', 1, 'C');
        $this->Cell($this->page_width - $this->w_margin, 0, '', 'T', 1);
    }

    /**
     *  Grupo, Jornada, Director, Fecha
     */
    private function headingSubTitle()
    {
        $this->SetFillColor(228, 229, 230);
        $this->SetFont('Arial', '', 8);
        $this->Cell(45, 5, self::transformMay('Grupo: ' . $this->vector->name), 'L', 0, 'C', true);
        $this->Cell(50, 5, self::transformMay('Jornada: ' . $this->vector->working_day_name), '', 0, 'C', true);
        $this->Cell(110, 5, self::transformMay('Director: ' . $this->vector->director_name), '', 0, 'C', true);
        $this->Cell(54.4, 5, self::transformMay('Fecha: ' . date("Y-m-d")), 'R', 1, 'C', true);
    }

    /**
     *  Método publico para generar el documento de formato pdf
     */
    public function createConsolidated($name_pdf)
    {
        foreach ($this->enrollments_by_groups as $key => $vector) {
            $this->setConfig($vector);
            //Genera el contenido
            $this->bodyTable($vector);
        }
        $this->Output('D', $name_pdf . '.pdf', true);
    }

    /**
     * @param $vector ,
     */
    private function setConfig($vector)
    {
        $this->vector = $vector;
        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(76, 76, 76);
        $this->page_width = $this->GetPageWidth();
        $this->size_column = $this->getSizeColumn($vector);

        // Al llamar este método, internamente se invoca el método Header
        $this->AddPage();

    }

    /**
     * @param $vector
     */
    private function headerTable($vector)
    {
        $this->SetFillColor(208, 233, 251);
        $this->SetFont('Arial', 'B', 6.6);
        $this->columnHeaderFixed();
        $this->columnHeaderSubjects($vector);
    }

    /**
     *
     */
    private function columnHeaderFixed()
    {
        $this->Cell($this->w_num, $this->h_cell + 1, 'No.', 1, 0, 'C', true);
        $this->Cell($this->w_name, $this->h_cell + 1, 'APELLIDOS Y NOMBRES', 1, 0, 'C', true);
        $this->Cell($this->w_per, $this->h_cell + 1, 'PER', 1, 0, 'C', true);
        $this->Cell($this->w_tav, $this->h_cell + 1, 'TAV', 1, 0, 'C', true);
        $this->Cell($this->w_rat, $this->h_cell + 1, 'PUESTO', 1, 0, 'C', true);
        $this->Cell($this->w_pgg, $this->h_cell + 1, 'PGG', 1, 0, 'C', true);
    }

    /**
     * @param $vector
     */
    private function columnHeaderSubjects($vector)
    {
        foreach ($vector->subjects as $key => $subject) {
            $this->Cell($this->size_column, $this->h_cell + 1, utf8_decode($subject->abbreviation), 1, 0, 'C', true);
        }
        $this->ln();
    }

    /**
     * @param $vector
     */
    private function bodyTable($vector)
    {
        foreach ($vector->enrollments as $key => $enrollment) {
            $this->columnsBodyTableFixed($enrollment, $key + 1);
            $this->forEachEvaluatedPeriods($enrollment, $vector);
        }
    }

    /**
     * @param $enrollment
     * @param $count
     */
    private function columnsBodyTableFixed($enrollment, $count)
    {
        $this->Cell($this->w_num, $this->h_cell, $count, 1, 0, 'C');
        $this->CellFullName($enrollment);
        $this->Cell($this->w_per, $this->h_cell, $this->params->period_selected_id, 1, 0, 'C');
    }


    /**
     * @param $enrollment
     * @param $vector
     */
    private function forEachEvaluatedPeriods($enrollment, $vector)
    {
        foreach ($enrollment->evaluatedPeriods as $rowEvaluatePeriod) {

            if ($rowEvaluatePeriod['period_id'] == $this->params->period_selected_id) {

                $this->Cell($this->w_tav, $this->h_cell, $rowEvaluatePeriod['tav'], 1, 0, 'C');
                $this->Cell($this->w_rat, $this->h_cell, $rowEvaluatePeriod['rating'], 1, 0, 'C');
                $this->Cell($this->w_pgg, $this->h_cell, $rowEvaluatePeriod['average'], 1, 0, 'C');

                $this->searchNotes($vector, $rowEvaluatePeriod['notes']);
            }
        }
    }


    /**
     * @param $vector
     * @param $notes
     */
    private function searchNotes($vector, $notes)
    {
        foreach ($vector->subjects as $key => $subject) {
            $state = false;
            foreach ($notes as $note) {
                if ($subject->asignatures_id == $note->asignatures_id) {
                    $value = self::processNote($note->value, $note->overcoming);
                    $this->Cell($this->size_column, $this->h_cell, ROUND($value, 1), 1, 0, 'C');
                    $state = true;
                }
            }
            if(!$state)
                $this->Cell($this->size_column, $this->h_cell, '', 1, 0, 'C');

        }
        $this->ln();
    }

    /**
     * @param $note
     * @param $overcoming
     * @return int
     */
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

    private function CellFullName($enrollment)
    {
        $this->SetFont('Arial', '', 7);
        $this->Cell($this->w_name, $this->h_cell, self::transformFullName($enrollment), 1, 0, 'L');
        $this->SetFont('Arial', '', 8);
    }

    private static function transformFullName($enrollment)
    {
        return substr(utf8_decode($enrollment->student_last_name . ' ' . $enrollment->student_name), 0, 29);
    }

    private function getSizeColumn($vector)
    {
        if (count($vector->subjects))
            return ($this->page_width - $this->size_column_fixed) / count($vector->subjects);
        else
            return ($this->page_width - $this->size_column_fixed);
    }

    private function getSizeMaxColumnFixed()
    {
        $this->size_column_fixed =
            $this->w_num + $this->w_per + $this->w_tav + $this->w_pgg + $this->w_rat + $this->w_name + $this->w_margin;
    }

    private static function transformMay($string)
    {
        return strtoupper(utf8_decode($string));
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