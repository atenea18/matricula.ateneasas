<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 7/08/2018
 * Time: 9:51 PM
 */

namespace App\Helpers\Statistics\Reprobated\Export;


use App\Helpers\Statistics\ParamsStatistics;
use App\Traits\utf8Helper;
use setasign\Fpdi\Fpdi;


class ExportPdf extends Fpdi
{
    use utf8Helper;

    private $params = null;
    private $page_width = null;
    private $w_dinamic_column = null;
    private $size_fixed_column = null;
    private $row_by_groups = [];
    private $num_is_accumulated = 0;

    // Se define el width de cada celda
    private $w_num = 5;
    private $w_period = 6;
    private $w_margin = 20;
    private $w_name_enroll = 96;
    private $w_name_asignature = 120;

    // Se define el height de todas las celdas
    private $h_cell = 3.109;

    private $data_row_current = null;

    public function __construct(string $orientation = 'Landscape', string $size = 'Letter', $row_by_groups, ParamsStatistics $params)
    {
        $this->params = $params;
        $this->row_by_groups = $row_by_groups;

        $this->getWeightTotalFixedColumn();
        parent::__construct($orientation, 'mm', $size);

    }


    public function Header()
    {
        if ($this->data_row_current) {
            $this->SetLineWidth(0.001);
            $this->SetDrawColor(76, 76, 76);

            $this->TitleInstitutionHeader();
            $this->TitleInformationGroupHeader();

            $this->HeaderTable($this->data_row_current);
        }
    }


    private function TitleInstitutionHeader()
    {
        $this->SetY(4);
        // Select Arial bold 15
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($this->page_width - $this->w_margin, 5.6, $this->transformMay($this->params->institution_object->name), 'LTR', 1, 'C');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->page_width - $this->w_margin, 4, $this->transformMay($this->data_row_current->headquarter_name), 'LR', 1, 'C');

        $title_consolidate = $this->params->is_accumulated == "true" ? "REPROBADOS CON PERIODOS ACUMULADOS" : "REPROBADOS";
        $this->Cell($this->page_width - $this->w_margin, 4, $this->transformMay($title_consolidate), 'LR', 1, 'C');
        $this->Cell($this->page_width - $this->w_margin, 0, '', 'T', 1);
    }

    private function TitleInformationGroupHeader()
    {
        $this->SetFillColor(228, 229, 230);
        $this->SetFont('Arial', '', 7);
        $this->Cell(45, 4, $this->transformMay('Grupo: ' . $this->data_row_current->name), 'L', 0, 'C', true);
        $this->Cell(50, 4, $this->transformMay('Jornada: ' . $this->data_row_current->working_day_name), '', 0, 'C', true);
        $this->Cell(110, 4, $this->transformMay('Director: ' . $this->data_row_current->director_name), '', 0, 'C', true);
        $this->Cell(54.4, 4, $this->transformMay('Fecha: ' . date("Y-m-d")), 'R', 1, 'C', true);
    }

    private function HeaderTable($row_current)
    {
        $this->SetFillColor(208, 233, 251);
        $this->SetFont('Arial', 'B', 6.6);
        $this->columnHeaderTitles($row_current);

    }

    private function columnHeaderTitles($row_current)
    {
        $this->Cell($this->w_num, $this->h_cell, 'No.', 1, 0, 'C', true);
        $this->Cell($this->w_name_enroll, $this->h_cell, self::transformMay('NOMBRE DE ESTUDIANTES'), 1, 0, 'C', true);
        $this->Cell($this->w_period, $this->h_cell, 'PER', 1, 0, 'C', true);
        $this->Cell($this->w_name_asignature, $this->h_cell, self::transformMay('ASIGNATURA/ÁREA'), 1, 0, 'C', true);
        $this->Cell($this->w_dinamic_column, $this->h_cell, self::transformMay('VALORACIÓN'), 1, 0, 'C', true);
        $this->ln();
    }


    public function create($name_pdf)
    {
        foreach ($this->row_by_groups as $row_current) {
            if (count($row_current->vector_data))
                $this->BodyTable($row_current);
        }
        $this->Output('D', $name_pdf . '.pdf', true);
    }

    private function BodyTable($row_current)
    {
        $this->setConfig($row_current);


        foreach ($row_current->vector_data as $key => $data) {
            $this->columnsBodyTable($data, $key + 1);
        }


    }

    private function setConfig($row_current)
    {
        $this->data_row_current = $row_current;
        $this->page_width = $this->GetPageWidth();
        $this->w_dinamic_column = $this->getWeightDinamicColumn(1);

        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(76, 76, 76);
        $this->SetFont('Arial', '', 7.4);

        $this->AddPage();
    }

    private function columnsBodyTable($data, $count)
    {
        $period_id = $this->params->is_accumulated == "true"?$data->periods_id:$this->params->period_selected_id;
        if($period_id == $data->periods_id){
            if(isset($data->enrollments)){
                foreach ($data->enrollments as $key_enroll => $enrollment){
                    $count_asignatures = count($enrollment->asignatures);
                    $this->Cell($this->w_num, $this->getHeightColumnNum() * $count_asignatures, $key_enroll+1, 1, 0, 'C');
                    $this->Cell($this->w_name_enroll, $this->getHeightColumnName() * $count_asignatures,
                        substr(self::transformMay($enrollment->student_last_name.' '.$enrollment->student_name),0, 64), 1, 0, 'L');
                    $this->Cell($this->w_period, $this->getHeightColumnNum() * $count_asignatures, $data->periods_id, 1, 0, 'C');
                    foreach ($enrollment->asignatures as $asignature){
                        $this->SetX($this->w_num+$this->w_name_enroll+$this->w_period+($this->w_margin/2));
                        $this->Cell($this->w_name_asignature, $this->getHeightColumnNum(), self::transformMay($asignature->name_subjects), 1, 0, 'C');
                        $this->Cell($this->w_dinamic_column, $this->getHeightColumnNum(), $asignature->value, 1, 0, 'C');
                        $this->ln();
                    }

                }
            }

        }



        //$this->ColumnsValoration($subject);
    }

    private function ColumnsValoration($subject)
    {
        foreach ($subject->vectorPeriods as $period) {

            if ($period->period_id == $this->params->period_selected_id) {
                $this->Cell($this->w_per, $this->h_cell, $period->period_id, 1, 0, 'C');
                $this->Cell($this->w_tav, $this->h_cell, $period->num_enrollment, 1, 0, 'C');
                $average = $period->num_enrollment > 0 ? round(($period->sum_value / $period->num_enrollment), 1) : 0;
                $this->setDanger($average, $this->params->middle_point);
                $this->Cell($this->w_rat, $this->h_cell, $average, 1, 0, 'C');
                $name_performance = '';
                foreach ($this->params->vectorScales as $scale) {
                    if ($average >= $scale->rank_start && $average <= $scale->rank_end) {
                        $name_performance = self::transformMay($scale->name);
                    }
                }
                $this->Cell($this->w_pgg, $this->h_cell, $name_performance, 1, 0, 'C');
                $this->setTextBlack();

                foreach ($period->vectorScales as $scale) {
                    $this->Cell($this->w_dinamic_column / 2, $this->h_cell, $scale->counter ? $scale->counter : '', 1, 0, 'C');
                    if ($period->num_enrollment > 0 && $scale->counter > 0)
                        $this->Cell($this->w_dinamic_column / 2, $this->h_cell, round((($scale->counter / $period->num_enrollment) * 100), 1) . '%', 1, 0, 'C');
                    else
                        $this->Cell($this->w_dinamic_column / 2, $this->h_cell, '', 1, 0, 'C');
                }
                $this->ln();
            }
        }
    }


    private function getHeightColumnNum()
    {
        if ($this->params->is_accumulated == "true")
            return $this->h_cell;
        //return $this->h_cell * ($this->params->num_of_periods + $this->num_is_accumulated);
        else
            return $this->h_cell;
    }

    private function getHeightColumnName()
    {
        if ($this->params->is_accumulated == "true")
            return $this->h_cell;
        //return $this->h_cell * $this->params->num_of_periods;
        else
            return $this->h_cell;
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


    private function getWeightDinamicColumn($count_title_dinamic)
    {
        if ($count_title_dinamic > 0)
            return ($this->page_width - $this->size_fixed_column) / $count_title_dinamic;
        else
            return ($this->page_width - $this->size_fixed_column);
    }

    private function getWeightTotalFixedColumn()
    {
        $this->size_fixed_column =
            $this->w_num + $this->w_period + $this->w_name_asignature + $this->w_name_enroll + $this->w_margin;
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
        $this->Cell(0, 4, utf8_decode('Atenea - P獺gina ' . $this->PageNo()), 0, 0, 'C');
    }


}