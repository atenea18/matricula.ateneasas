<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 7/08/2018
 * Time: 9:51 PM
 */

namespace App\Helpers\Statistics\Percentage\Export;


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
    private $subjects_by_groups = [];
    private $num_is_accumulated = 0;

    // Se define el width de cada celda
    private $w_num = 5;
    private $w_per = 6;
    private $w_tev = 7;
    private $w_desem = 18;
    private $w_pgg = 18;
    private $w_name = 96;
    private $w_margin = 20;

    // Se define el height de todas las celdas
    private $h_cell = 3.109;

    private $subjectByGroup = null;

    public function __construct(string $orientation = 'Landscape', string $size = 'Letter', $subjects_by_groups, ParamsStatistics $params)
    {
        $this->params = $params;
        $this->subjects_by_groups = $subjects_by_groups;

        $this->getSizeMaxColumnFixed();
        parent::__construct($orientation, 'mm', $size);

    }


    public function Header()
    {
        if ($this->subjectByGroup) {
            $this->SetLineWidth(0.001);
            $this->SetDrawColor(76, 76, 76);

            $this->TitleInstitutionHeader();
            $this->TitleInformationGroupHeader();

            $this->HeaderTable($this->subjectByGroup);
        }
    }


    private function TitleInstitutionHeader()
    {
        $this->SetY(4);
        // Select Arial bold 15
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($this->page_width - $this->w_margin, 5.6, $this->transformMay($this->params->institution_object->name), 'LTR', 1, 'C');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->page_width - $this->w_margin, 4, $this->transformMay($this->subjectByGroup->headquarter_name), 'LR', 1, 'C');

        $title_table = $this->params->is_accumulated == "true" ? "PORCENTUAL CON PERIODOS ACUMULADOS" : "PORCENTUAL";
        $this->Cell($this->page_width - $this->w_margin, 4, $this->transformMay($title_table), 'LR', 1, 'C');
        $this->Cell($this->page_width - $this->w_margin, 0, '', 'T', 1);
    }

    private function TitleInformationGroupHeader()
    {
        $this->SetFillColor(228, 229, 230);
        $this->SetFont('Arial', '', 7);
        $this->Cell(45, 4, $this->transformMay('Grupo: ' . $this->subjectByGroup->name), 'L', 0, 'C', true);
        $this->Cell(50, 4, $this->transformMay('Jornada: ' . $this->subjectByGroup->working_day_name), '', 0, 'C', true);
        $this->Cell(110, 4, $this->transformMay('Director: ' . $this->subjectByGroup->director_name), '', 0, 'C', true);
        $this->Cell(54.4, 4, $this->transformMay('Fecha: ' . date("Y-m-d")), 'R', 1, 'C', true);
    }

    private function HeaderTable($subjectByGroup)
    {
        $this->SetFillColor(208, 233, 251);
        $this->SetFont('Arial', 'B', 6.6);
        $this->columnHeaderTitles($subjectByGroup);

    }

    private function columnHeaderTitles($subjectByGroup)
    {
        $this->Cell($this->w_num, $this->h_cell, 'No.', 1, 0, 'C', true);
        $this->Cell($this->w_name, $this->h_cell, self::transformMay('NOMBRE DE ÁREAS/ASIGNATURAS'), 1, 0, 'C', true);
        $this->Cell($this->w_per, $this->h_cell, 'PER', 1, 0, 'C', true);
        $this->Cell($this->w_tev, $this->h_cell, 'TEV', 1, 0, 'C', true);
        $this->Cell($this->w_pgg, $this->h_cell, 'PROMEDIO', 1, 0, 'C', true);
        $this->Cell($this->w_desem, $this->h_cell, self::transformMay('DESEMPEÑO'), 1, 0, 'C', true);

        //Encabezado de asignaturas
        foreach ($subjectByGroup->scales as $scale) {
            $this->Cell($this->size_column, $this->h_cell, utf8_decode($scale->name), 1, 0, 'C', true);
        }
        $this->ln();
    }


    public function create($name_pdf)
    {
        foreach ($this->subjects_by_groups as $subjectByGroup) {
            if (count($subjectByGroup->subjects))
                $this->BodyTablePercentage($subjectByGroup);
        }
        $this->Output('D', $name_pdf . '.pdf', true);
    }

    private function BodyTablePercentage($subjectByGroup)
    {
        $this->setConfig($subjectByGroup);

        foreach ($subjectByGroup->subjects as $key => $subject) {
            $this->columnsBodyTablePercentage($subject, $key + 1);
        }

    }

    private function setConfig($subjectByGroup)
    {
        $this->subjectByGroup = $subjectByGroup;
        $this->page_width = $this->GetPageWidth();
        $this->size_column = $this->getSizeColumn($subjectByGroup);

        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(76, 76, 76);
        $this->SetFont('Arial', '', 7.4);

        $this->AddPage();
    }

    private function columnsBodyTablePercentage($subject, $count)
    {
        $this->Cell($this->w_num, $this->getHeightColumnNum(), $count, 1, 0, 'C');
        $this->Cell($this->w_name, $this->getHeightColumnName(), substr(self::transformMay($subject->name),0,64), 1, 0, 'L');

        $this->ColumnsValoration($subject);
    }

    private function ColumnsValoration($subject)
    {
        foreach ($subject->vectorPeriods as $period) {
            $period_selected = $this->params->is_accumulated=="true"?$period->period_id:$this->params->period_selected_id;

            if ($period->period_id == $period_selected) {
                $this->SetX($this->w_num+ $this->w_name + ($this->w_margin/2));
                $this->Cell($this->w_per, $this->h_cell, $period->period_id, 1, 0, 'C');
                $this->Cell($this->w_tev, $this->h_cell, $period->num_enrollment, 1, 0, 'C');
                $average = $period->num_enrollment>0?round(($period->sum_value/$period->num_enrollment),1):0;
                $this->setDanger($average, $this->params->middle_point);
                $this->Cell($this->w_pgg, $this->h_cell, $average, 1, 0, 'C');
                $name_performance = '';
                foreach ($this->params->vectorScales as $scale){
                    if($average >= $scale->rank_start && $average <= $scale->rank_end){
                        $name_performance = self::transformMay($scale->name);
                    }
                }
                $this->Cell($this->w_desem, $this->h_cell,$name_performance, 1, 0, 'C');
                $this->setTextBlack();

                foreach ($period->vectorScales as $scale) {
                    $this->Cell($this->size_column/2, $this->h_cell, $scale->counter?$scale->counter:'', 1, 0, 'C');
                    if($period->num_enrollment>0 && $scale->counter > 0 )
                        $this->Cell($this->size_column/2, $this->h_cell, round((($scale->counter/$period->num_enrollment)*100),1).'%', 1, 0, 'C');
                    else
                        $this->Cell($this->size_column/2, $this->h_cell, '', 1, 0, 'C');
                }
                $this->ln();
            }
        }
    }



    private function getHeightColumnNum()
    {
        if ($this->params->is_accumulated == "true")
            return $this->h_cell * $this->params->num_of_periods;
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


    private function getSizeColumn($subjectByGroup)
    {
        if (count($subjectByGroup->scales))
            return ($this->page_width - $this->size_column_fixed) / count($subjectByGroup->scales);
        else
            return ($this->page_width - $this->size_column_fixed);
    }

    private function getSizeMaxColumnFixed()
    {
        $this->size_column_fixed =
            $this->w_num + $this->w_per + $this->w_tev + $this->w_desem + $this->w_pgg + $this->w_name + $this->w_margin;
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