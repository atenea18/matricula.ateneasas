<?php

namespace App\Pdf\Notebook;

/**
 *
 */
use Illuminate\Support\Facades\Storage;
use Codedge\Fpdf\Fpdf\Fpdf;

use App\Traits\utf8Helper;

class GeneralReport extends Fpdf
{
    use utf8Helper;

    private $data = array();
    private $content = array();

    private $_h_c = 4;

    function header()
    {
        // Logo
        if ($this->data['institution']['picture'] != NULL) {
            try {

                $this->Image(
                    Storage::disk('uploads')->url(
                        $this->data['institution']->picture
                    ), 12, 12, 17, 17);

            } catch (\Exception $e) {
            }
        }

        //Marco
        $this->Cell(0, 24, '', 1, 0);
        $this->Ln(0);

        // NOMBRE DE LA INSTITUCIÓN
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, $this->hideTilde($this->data['institution']->name), 0, 0, 'C');
        $this->Ln(6);

        $this->SetFont('Arial', 'B', 9);
        // NOMBRE DE LA SEDE
        if (!empty($this->data['headquarter'])):
            $this->Cell(0, 4, 'SEDE: ' . strtoupper(($this->data['headquarter']->name)), 0, 0, 'C');

        endif;

        $this->Ln(4);

        // TITULO DEL PDF
        $this->Cell(0, 4, strtoupper($this->data['tittle_general_report']), 0, 0, 'C');
        $this->Ln();

        // NOMBRE DEL GRUPO
        $this->SetFont('Arial', '', 8);
        $this->Cell(20, 4, '', 0, 0);
        $this->Cell(90, 4, 'GRUPO: ' . $this->data['group']->name, 0, 0, 'L');

        // DIRECTOR DE GRUPO
        $this->Cell(0, 4, $this->hideTilde('DIR. DE GRUPO: ' .
            $this->data['director']->fullName), 0, 0, 'L');
        $this->Ln();

        // NOMBRE DEL ESTUDIANTE
        $this->Cell(20, 4, '', 0, 0);
        $this->Cell(90, 4, 'ESTUDIANTE: ' . $this->hideTilde(
                $this->data['student']->fullNameInverse
            ), 0, 0, 'L');

        // FECHA
        $this->Cell(0, 4, 'FECHA: ' . $this->data['date'], 0, 0, 'L');
        // Salto de línea
        $this->Ln(8);

        $this->subHeader();
    }

    public function subHeader()
    {
        $this->Cell(0, 222, '', 1, 0);
        $this->Ln(0);
    }

    public function setData($data)
    {
        $this->data = $data;
        if($data['general_report'] != null)
            $this->content = explode('<p>', $data['general_report']->report);

    }

    public function create()
    {
        // AÑADIMOS UNA PAGINA EN BLANCO
        $this->addPage();

        // AÑADIMOS LA FUENTE Y EL TAMAÑO
        $this->SetFont('Arial', '', 10);

        $border = 0;
        foreach ($this->content as $key => $p):
            $this->determineCell($this->hideTilde($p), $border);
        endforeach;

        // Activanos el doble cara
        if ($this->data['config']['doubleFace']):
            $this->DoubleFace();
        endif;
    }

    /**
     *
     *
     */
    private function determineCell($data, $border)
    {
        // $this->SetFont('Arial','',8);

        if (strlen($data) > 100 && strlen($data) > 0) {
            $this->MultiCell(0, $this->_h_c, strip_tags($data), $border, 'L');
        } else if (strlen($data) > 0) {
            $this->Cell(0, $this->_h_c, strip_tags($data), $border,0, 'L');

        }

        $this->Ln(4);
    }

    /**
     *
     *
     */
    private function DoubleFace()
    {
        if ($this->PageNo() % 2 != 0 && $this->PageNo() >= 1):
            $this->AddPage();
        endif;
    }

    function footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-20);

        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 5, $this->hideTilde('@tenea - Página ') . $this->PageNo(), 0, 0, 'C');
    }
}