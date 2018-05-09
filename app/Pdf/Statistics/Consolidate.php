<?php

namespace App\Pdf\Statistics;

/**
* 
*/
use Illuminate\Support\Facades\Storage;
use Codedge\Fpdf\Fpdf\Fpdf;

use App\Group;

class Consolidate extends Fpdf
{

	public $tipo = 'Consolidado';

	public $group;
	public $institution;

	public $asignatures = array();
	public $content = array();

	private $_width_mark = 267;
	private $_with_CE = 75;
	private $_with_CD = 5.7;
	private $_with_CA = 10;

	public function Header()
	{
		// To be implemented in your own inherited class
		// Logo

		try
		{
			if($this->institution->picture != NULL)
			$this->Image(
				Storage::disk('uploads')->url(
					$this->institution->picture

				), 12, 14, 17, 17);
		}catch(\Exception $e){}


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
	    	$this->Cell(0,4, utf8_decode($this->group->headquarter->name), 0, 0, 'C');
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
	    (count($this->institution->headquarters) > 1) ? $this->Ln(4) : $this->Ln(7) ;

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
		$this->SetFont('Arial','B',7);

		if(count($this->asignatures) > 19)
		{
			$this->_with_CA = 8.5;
			$this->_with_CE = 72;	
		}

		$this->Cell(5, 4, '#', 1, 0, 'C', true);
		$this->Cell($this->_with_CE, 4, 'APELLIDOS Y NOMBRES DE ESTUDIANTE', 1,0, 'C', true);
		$this->Cell(8, 4, 'TAV', 1, 0, 'C', true);
		
		//
		foreach($this->asignatures as $key => $asignature)
		{
			$this->Cell($this->_with_CA, 4, substr(utf8_decode($asignature->abbreviation), 0, 3), 1, 0, 'C', true);	
		}

		$this->Ln(4);
	}

	public function create()
	{
		$this->AddPage();
		$this->SetFont('Arial','',7);

		foreach($this->content as $key => $student)
		{
			$this->Cell(5, 4, ($key+1), 1, 0, 'C', false);
			$this->Cell($this->_with_CE, 4, utf8_decode("{$student->student_last_name} {$student->student_name}"), 1, 0, 'L', false);	
			
			// Mostramos el tav
			$this->Cell(8, 4, $this->getTav($student), 1, 0, 'C', false);
			
			// Mostramos las asignaturas
			$this->showAsignatures($student);

			$this->Ln();
		}

	}

	private function getTav($student)
	{

		$hits = 0;

		foreach ($student->notes_final as $key => $note) {

			foreach($this->asignatures as $keyA => $asignature)
			{
				if($note->asignatures_id == $asignature->asignatures_id)
					$hits++;
			}
		}

		return $hits;
	}

	private function showAsignatures($student)
	{

		foreach($this->asignatures as $keyA => $asignature)
			$this->Cell($this->_with_CA, 4, $this->getNote($student, $asignature), 1, 0, 'C', false);
	}

	private function getNote($student, $asignature)
	{
		foreach ($student->notes_final as $key => $note) 
			if($note->asignatures_id == $asignature->asignatures_id)
				return round($note->value,2)!=0?round($note->value,2):'';

		return "";
	}

	private function showFooterAsignatures()
	{
		$this->SetFont('Arial','',6);
		$content = '';

		foreach($this->asignatures as $key => $asignature)
		{
			$content .= ucwords($asignature->abbreviation." : ".$asignature->name." - ");
		}

		// $this->SetFont('Arial','I',6);
		$this->MultiCell(0, 4, utf8_decode($content), 0, 'L');

	}
	public function Footer()
	{
		// Posición: a 1,5 cm del final
	    $this->SetY(-20);

	    // 
	    $this->showFooterAsignatures();

	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Número de página
	    $this->Cell(0,10,utf8_decode('@Atenea - Página ').$this->PageNo(),0,0,'C');
	}
}