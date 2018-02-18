<?php

namespace App\Pdf\Sheet;

/**
* 
*/
use Illuminate\Support\Facades\Storage;
use Codedge\Fpdf\Fpdf\Fpdf;

class StudentAttendance extends Fpdf
{

	public $tipo = 'Planilla De Asistencia';
	public $infoGroupAndAsig = array();
	public $institution = array();
	public $group = array();
	private $_width_mark = 267;

	private $_with_CE = 75;
	private $_with_CD = 5.7;
	
	public function Header()
	{
		// To be implemented in your own inherited class
		// Logo
		if($this->institution->picture != NULL)
			$this->Image(
				Storage::disk('uploads')->url(
					$this->institution->picture
				), 12, 14, 17, 17, "PNG");

		//Marco
	    $this->Cell($this->_width_mark,24, '', 1,0);
	    $this->Ln(0);

	    // PRIMERA LINEA
	    $this->SetFont('Arial','B',14);
	    // Movernos a la dereca
	    // $this->Cell(90, 6, '', 0,0);
	    // Título
	    $this->Cell(0, 6, utf8_decode($this->institution->name), 0, 0, 'C');
	    // Movernos a la derecha
	    // $this->Cell(0, 6, '', 0,0);
	    // Salto de línea
	    $this->Ln(6);

	    // SEGUNDA LINEA
	    $this->SetFont('Arial','B',9);
	    // $this->Cell(90, 4, '', 0,0);
	    
	    // Título
	    if(count($this->institution->headquarters) > 1){
	    	$this->Cell(0,4, $this->group->headquarter->name, 0, 0, 'C');
	    	$this->Ln(4);	
	    }
	    // Movernos a la derecha
	    // $this->Cell(0, 4, '', 0,0);
	    // Salto de línea

	    // TERCERA LINEA
	    // $this->Cell(90, 4, '', 0,0);
	    // Título
	    $this->Cell(0, 4, $this->tipo, 0, 0, 'C');
	    // Movernos a la derecha
	    // $this->Cell(0, 4, '', 0,0);
	    // Salto de línea
	    $this->Ln(4);

	    // CUARTA LINEA
	    $this->SetFont('Arial','',8);
	    // Movernos a la derecha
	    $this->Cell(25, 4, '', 0,0);
	    $this->Cell(75, 4, 'GRUPO: '.$this->group->name, 0, 0, 'L');
	    // Título
	    $this->Cell(100,4, '', 0, 0, 'C');
	    // Movernos a la derecha
	    $this->Cell(0, 4, utf8_decode('AÑO LECTIVO ').date('Y'), 0,0);
	    // Salto de línea
	    $this->Ln(4);

	    // QUINTA LINEA
	    // Movernos a la derecha
	    $this->Cell(25, 4, '', 0,0);
	    $this->Cell(75, 4, '', 0, 0, 'L');
	    // Título
	    $this->Cell(100,4, ' ', 0, 0, 'L');
	    // Movernos a la derecha
	    $this->Cell(0,4, 'FECHA: '.date('d-m-Y'), 0, 0, 'L');
	    // Salto de línea
	    $this->Ln(8);

	    // 
	    $this->subheader();
	}

	private function subheader()
	{
		// Salto de linea
		$this->Ln(4);
		// 
		$this->SetFillColor(172, 220, 240);
		// 
		$this->SetFont('Arial','B',9);

		$this->Cell($this->_with_CE, 4, 'APELLIDOS Y NOMBRES DE ESTUDIANTE', 1,0, 'C', true);
		$this->Cell(8, 4, 'NOV', 1, 0, 'C', true);
		$this->Cell(8, 4, 'EST', 1, 0, 'C', true);
		for ($i=0; $i <31 ; $i++)
			if($i < 9)
				$this->Cell($this->_with_CD, 4, '0'.($i+1), 1, 0, 'C', true);
			else
				$this->Cell($this->_with_CD, 4, ($i+1), 1, 0, 'C', true);

		$this->Ln(4);
	}

	public function create($students)
	{
		$this->AddPage();
		$this->SetFont('Arial','',8);

		$cont = 1;
		foreach ($students as $key => $student) {
			
			if($cont <= 9)
			{
				$this->Cell($this->_with_CE, 4, '0'.($cont++).' '.utf8_decode($student->fullNameInverse)
				, 1,0);	
			}
			else
			{
				$this->Cell($this->_with_CE, 4, ($cont++).' '.utf8_decode($student->fullNameInverse)
				, 1,0);
			}
			
			// Mostramos la novedad
			$this->Cell(8, 4, '', 1,0);
			
			// Mostramos el estado
			$this->Cell(8, 4, $student->state->state, 1,0);

			

			for ($i=0; $i < 31; $i++) { 
				$this->Cell($this->_with_CD, 4, '', 1, 0, 'C');
			}

			$this->Ln(4);
		}
	}

	public function Footer()
	{
		// Posición: a 1,5 cm del final
	    $this->SetY(-18);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Número de página
	    $this->Cell(0,10,utf8_decode('@Atenea - Página ').$this->PageNo(),0,0,'C');
	}
}