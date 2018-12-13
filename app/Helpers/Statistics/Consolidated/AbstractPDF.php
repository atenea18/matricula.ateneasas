<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 12/11/2018
 * Time: 2:10 AM
 */

namespace App\Helpers\Statistics\Consolidated;

use App\Helpers\Statistics\ParamsStatistics;
use App\Traits\utf8Helper;
use setasign\Fpdi\Fpdi;

Abstract class AbstractPDF extends Fpdi
{
    use utf8Helper;
    protected $table_ = null;
    protected $page_ = null;
    protected $dynamic_ = null;

    protected $params = null;
    protected $enrollmentByGroup = null;
    protected $statistical_title = "";

    public function __construct(string $orientation = 'Landscape', string $size = 'Letter')
    {
        parent::__construct($orientation, 'mm', $size);

        $this->table_ = (object)[
            'cells' => [],
            'high_cell' => 3.109,
        ];
        $this->page_ = (object)[
            'width' => 274,
            'margin' => 20,
        ];

        $this->dynamic_ = (object)[
            'high' => 5,
            'width' => 0,
            'number' => 0,
        ];
    }

    protected function setTableCells($cells)
    {
        $this->table_->cells = $cells;
    }

    protected function setParams(ParamsStatistics $params)
    {
        $this->params = $params;
    }

    protected function setEnrollmentByGroup($enrollBygroup)
    {
        $this->enrollmentByGroup = $enrollBygroup;
    }

    protected function setStatisticalTitle($title, $title_)
    {
        $this->statistical_title = $title;
        if ($this->params->is_accumulated == "true")
            $this->statistical_title = $title_;
    }

    protected function setConfig()
    {
        $this->page_->width = $this->GetPageWidth();
        $this->page_->margin = 20;
        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(76, 76, 76);
        $this->SetFont('Arial', '', 7.4);
        $this->AddPage();
    }

    protected function setNumberDynamicTitles($number)
    {
        $this->dynamic_->number = $number;
        $this->getDynamicWidth();
    }

    private function getDynamicWidth()
    {
        $width_ = $this->page_->width - $this->getSumWidthCells();
        if ($this->dynamic_->number > 0) {
            $this->dynamic_->width = $width_ / $this->dynamic_->number;
        }
    }

    private function getSumWidthCells()
    {
        $sum = $this->page_->margin;
        foreach ($this->table_->cells as $cell) {
            $sum += $cell->width;
        }
        return $sum;
    }

    public function Header()
    {
        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(76, 76, 76);
        $this->SetFont('Arial', '', 7.4);

        if ($this->enrollmentByGroup) {
            $this->SetLineWidth(0.001);
            $this->SetDrawColor(76, 76, 76);

            $this->TitleInstitutionHeader();
            $this->TitleInformationGroupHeader();
            $this->drawTableHeader();
        }
    }

    protected function drawTableHeader()
    {
        $this->drawTableTitles();
    }

    protected function drawTableTitles()
    {
        foreach ($this->table_->cells as $key => $cell_) {
            $this->Cell($cell_->width, $this->table_->high_cell, strtoupper($cell_->title), 1, 0, 'C');
        }
        $this->drawTableTitlesDynamic();
    }

    protected abstract function drawTableTitlesDynamic();


    protected function drawCellWithIndex($text, $key, $rows_pan = 1)
    {
        $this->Cell($this->table_->cells[$key]->width, ($this->table_->high_cell * $rows_pan), strtoupper(utf8_decode($text)), 1, 0, 'L');
    }

    protected function drawCell($text, $width)
    {
        $this->Cell($width, $this->table_->high_cell, strtoupper(utf8_decode($text)), 1, 0, 'L');
    }

    protected function drawCellWithDynamic($text)
    {
        $this->Cell($this->dynamic_->width, $this->table_->high_cell, utf8_decode($text), 1, 0, 'C');
    }

    private function TitleInstitutionHeader()
    {
        $this->SetY(4);
        // Select Arial bold 15
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($this->page_->width - $this->page_->margin, 5.6, $this->transformMay($this->params->institution_object->name), 'LTR', 1, 'C');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->page_->width - $this->page_->margin, 4, $this->transformMay($this->enrollmentByGroup->headquarter_name), 'LR', 1, 'C');
        $this->Cell($this->page_->width - $this->page_->margin, 4, $this->transformMay($this->statistical_title), 'LR', 1, 'C');
        $this->Cell($this->page_->width - $this->page_->margin, 0, '', 'T', 1);
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