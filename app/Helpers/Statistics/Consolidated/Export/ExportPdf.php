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
    private $title = '';
    private $params = null;
    private $page_width = null;
    private $enrollments_by_groups = [];

    public function __construct(string $orientation = 'Landscape',string $size = 'Letter', $enrollments_by_groups, ParamsStatistics $params)
    {
        $this->params = $params;
        $this->enrollments_by_groups = $enrollments_by_groups;
        parent::__construct($orientation, 'mm', $size);

    }

    public function Header()
    {
        // Select Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(80);
        // Framed title
        $this->Cell(30, 10, $this->title, 1, 0, 'C');
        // Line break
        $this->Ln(20);
    }

    public function createConsolidated(){
        $this->generate();
    }

    private function generate()
    {

        //$this->SetLineWidth(.01);
        foreach ($this->enrollments_by_groups as $key => $vector) {
            $this->title ='Hola'.$key;
            $this->AddPage();
            $this->page_width = $this->GetPageWidth();
            $this->SetFont('Times', '', 6.6);
            $this->SetTextColor(0, 0, 0);
            $this->subHeader($vector);
            $this->contentTable($vector);

        }

        $this->Output('D', 'consolidated-' . $this->params->group_object->name . '.pdf', true);
    }

    private function subHeader($vector)
    {
        $size_column = $this->getSizeColumn($vector);

        $this->Cell(5, 5, 'No.', 1, 0, 'C');
        $this->Cell(45, 5, 'APELLIDOS Y NOMBRES', 1, 0, 'C');

        $this->Cell(5, 5, 'PER', 1, 0, 'C');
        $this->Cell(7, 5, 'TAV', 1, 0, 'C');
        $this->Cell(10, 5, 'PUESTO', 1, 0, 'C');
        $this->Cell(7, 5, 'PGG', 1, 0, 'C');

        foreach ($vector->subjects as $key => $subject){
            $this->Cell($size_column, 5, utf8_decode($subject->abbreviation), 1, 0, 'C');
        }

        $this->ln();
    }

    private function contentTable($vector){

        $size_column = $this->getSizeColumn($vector);

        foreach ($vector->enrollments as $key => $enrollment) {
            $this->Cell(5, 5, $key+1, 1, 0, 'C');
            $this->CellName($enrollment);
            $this->Cell(5, 5, $this->params->period_selected_id, 1, 0, 'C');

            foreach ($enrollment->evaluatedPeriods as $rowEvaluatePeriod){

                if($rowEvaluatePeriod['period_id'] == $this->params->period_selected_id){

                    $this->Cell(7, 5, $rowEvaluatePeriod['tav'], 1, 0, 'C');
                    $this->Cell(10, 5, $rowEvaluatePeriod['rating'], 1, 0, 'C');
                    $this->Cell(7, 5, $rowEvaluatePeriod['average'], 1, 0, 'C');
                    foreach ($vector->subjects as $key => $subject){
                        foreach ($rowEvaluatePeriod['notes'] as $note){
                            if($subject->asignatures_id == $note->asignatures_id){
                                $value = self::processNote($note->value, $note->overcoming);
                                $this->Cell($size_column, 5, ROUND($value,1), 1, 0, 'C');
                            }

                        }

                    }
                    $this->ln();
                }
            }
        }

    }

    private  static function processNote($note, $overcoming)
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

    private function CellName($enrollment){
        $this->SetFont('Times', '', 6.7);
        $this->Cell(45, 5, self::name($enrollment), 1, 0, 'L');
        $this->SetFont('Times', '', 8);
    }

    private static function name($enrollment){
        return substr(utf8_decode($enrollment->student_last_name.' '.$enrollment->student_name),0,29);
    }

    private function getSizeColumn($vector){
        if(count($vector->subjects))
            return ($this->page_width - 100) / count($vector->subjects);
        else
            return ($this->page_width - 100);
    }



}