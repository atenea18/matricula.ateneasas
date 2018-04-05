<?php

namespace App\Pdf\Sheet;

/**
* 
*/
use Illuminate\Support\Facades\Storage;
use Codedge\Fpdf\Fpdf\Fpdf;

class EvaluationSheet extends Fpdf
{

	public $institution = array();
	public $parameters = array();
	public $group = array();
	public $title = "PLANILLA DE EVALUACIÓN";
 	
	// 
	private $_width_mark = 267; //Ancho del marco de la cabecera
	private $_height_mark = 20; //Alto del marco de la cabecera
	private $_with_CE = 70; //Ancho de la celda de estudiantes
	private $_with_C_N_E = 8; //Ancho de la celda novedad (NOV) y estatus (EST)
	private $_with_C_H = 50; //Ancho de la celda donde estan los header (Desempeños)
	private $_width_VG_VRA = 8; //Ancho de las celdas (VRA y VG)
	private $_with_title = 187; //Ancho de la celda del nombre de la institución
	private $_font_tittle = 14; //Tamaño de fuente del nombre de la institución
	private $_first_place_header = 25;
	private $_second_place_header = 75;
	private $_third_place_header = 100;

	private function _configMargin()
	{
		if($this->DefOrientation == 'P')
		{
			$this->_width_mark = 190;
			if(count($this->institution->headquarters) > 1){
		    	$this->_height_mark = 25;
		    }

			$this->_font_tittle = 12;
			$this->_with_title = 120;
			$this->_first_place_header = 20;
			$this->_second_place_header = 50;
			$this->_third_place_header = 90;
		}
	}

	public function Header()
	{

		$this->_configMargin();
		// To be implemented in your own inherited class
		// Logo
		if($this->institution->picture != NULL)
			$this->Image(
				Storage::disk('uploads')->url(
					$this->institution->picture
				), 12, 12, 17, 17, "PNG");

		//Marco
	    $this->Cell($this->_width_mark, $this->_height_mark, '', 1,0);
	    $this->Ln(2);

	    // PRIMERA LINEA
	    $this->SetFont('Arial','B', $this->_font_tittle);
	    // Movernos a la dereca
	    $this->Cell(40, 6, '', 0,0);
	    // Título
	    $this->Cell($this->_with_title, 6, (utf8_decode($this->institution->name)), 0, 0, 'C');
	    // Movernos a la derecha
	    $this->Cell(40, 6, '', 0,0);
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
	    $this->Cell(0, 4, utf8_decode($this->title), 0, 0, 'C');
	    // Movernos a la derecha
	    // $this->Cell(0, 4, '', 0,0);
	    // Salto de línea
	    $this->Ln(4);

	    // CUARTA LINEA
	    $this->SetFont('Arial','',8);
	    // Movernos a la derecha
	    $this->Cell($this->_first_place_header, 4, '', 0,0);
	    $this->Cell($this->_second_place_header, 4, 'GRUPO: '.$this->group->name, 0, 0, 'L');
	    // Título
	    $this->Cell($this->_third_place_header,4, ($this->group->director()->first() != null) ? 'DIRECTOR DE GRUPO: '.strtoupper(utf8_decode($this->group->director()->first()->manager->fullName)) : 'DIRECTOR DE GRUPO:', 0, 0, 'L');
	    // Movernos a la derecha
	    $this->Cell(0, 4, utf8_decode('AÑO LECTIVO ').date('Y'), 0,0);
	    // Salto de línea
	    $this->Ln(4);

	    // QUINTA LINEA
	    // Movernos a la derecha
	    $this->Cell($this->_first_place_header, 4, '', 0,0);
	    $this->Cell($this->_second_place_header, 4, 'ASIGNATURA:', 0, 0, 'L');
	    // Título
	    $this->Cell($this->_third_place_header,4, 'DOCENTE:', 0, 0, 'L');
	    // Movernos a la derecha
	    $this->Cell(0,4, 'FECHA: '.date('d-m-Y'), 0, 0, 'L');
	    
	    // Salto de línea
	    (count($this->institution->headquarters) > 1) ? $this->Ln(2) : $this->Ln(1) ;

	    // 
	    $this->subheader();
	}

	private function subheader()
	{
		// Salto de linea
		$this->Ln(4);
		// 
		$this->SetFillColor(225, 249, 255);
		// 
		$this->SetFont('Arial','B',9);

		// Show EvaluationSheet Parameters
		$this->showEvaluationParameters();


		$this->Cell($this->_with_CE, 4, 'APELLIDOS Y NOMBRES DE ESTUDIANTE', 1,0, 'C', true);
		$this->Cell($this->_with_C_N_E, 4, 'NOV', 1, 0, 'C', true);
		$this->Cell($this->_with_C_N_E, 4, 'EST', 1, 0, 'C', true);

		// Show Evaluation Criteria
		$this->showCriteria();

		$this->Ln(4);
	}

	private function showEvaluationParameters()
	{
		$this->Cell( (($this->_with_C_N_E * 2) + $this->_with_CE ), 4, '', 1,0, 'C', true);

		// show Parameter
		foreach($this->parameters as $parameter)
		{
			if(strtolower($parameter->abbreviation) == 'aee')
			{
				$this->Cell($this->_width_VG_VRA, 8, 'AEE', 1,0, 'C', true);
			}
			else
			{
				$this->Cell($this->_with_C_H, 4, utf8_decode($parameter->parameter), 1, 0, 'C', true);
			}
		}

		$this->Cell($this->_width_VG_VRA, 8, 'VG', 1,0, 'C', true);
        $this->Cell($this->_width_VG_VRA, 8, 'VRA', 1,0, 'C', true);
		$this->Cell($this->_width_VG_VRA, 8, 'Val', 1,0, 'C', true);

		$this->Ln(4);
	}

	private function showCriteria()
	{

		$this->SetFont('Arial','B',7);

		// show Criteria
		foreach($this->parameters as $parameter){

			$withCellCriteria = (count($parameter->criterias) > 0) ? (  $this->_with_C_H / (count($parameter->criterias)) ) : $this->_with_C_H;

			if(strtolower($parameter->abbreviation) != 'aee')
			{
				if(count($parameter->criterias) == 0)
				{
					$withCellCriteria = ($this->_with_C_H / 5);

					for ($i=0; $i < 5; $i++) { 
						$this->Cell($withCellCriteria , 4, '', 1, 0, 'C', true);
					}
				}
				else
				{

					foreach($parameter->criterias as $criteria)
						$this->Cell($withCellCriteria , 4, utf8_decode($criteria->abbreviation), 1, 0, 'C', true);
				}
			}
		}

	}

	public function create($students)
	{
		$this->AddPage();
		$this->SetFont('Arial','',7);

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
			$this->Cell($this->_with_C_N_E, 4, '', 1,0);
			
			// Mostramos el estado
			$this->Cell($this->_with_C_N_E, 4, $student->state->state, 1,0, 'C');

			// show field Criteria note
			foreach($this->parameters as $parameter){

				$withCellCriteria = (count($parameter->criterias) > 0) ? (  $this->_with_C_H / (count($parameter->criterias)) ) : $this->_with_C_H;

				if(strtolower($parameter->abbreviation) != 'aee')
				{
					if(count($parameter->criterias) == 0)
					{
						$withCellCriteria = ($this->_with_C_H / 5);

						for ($i=0; $i < 5; $i++) { 
							$this->Cell($withCellCriteria , 4, '', 1, 0, 'C', false);
						}
					}
					else
					{

						foreach($parameter->criterias as $criteria)
							$this->Cell($withCellCriteria , 4, '', 1, 0, 'C', false);
					}
				}
				else
				{
					$this->Cell($this->_width_VG_VRA, 4, '', 1,0, 'C', false);	
				}
			}

			// 
			$this->Cell($this->_width_VG_VRA, 4, '', 1,0, 'C', false);
	        $this->Cell($this->_width_VG_VRA, 4, '', 1,0, 'C', false);
			$this->Cell($this->_width_VG_VRA, 4, '', 1,0, 'C', false);

			$this->Ln(4);
		}
	}

	public function showCriteriaFooter()
	{
		$this->SetFont('Arial','',6);
		$content = '';
		
		foreach($this->parameters as $parameter){

			if(count($parameter->criterias) > 0)
			{
				foreach($parameter->criterias as $criteria)
					$content .= ucwords($criteria->abbreviation)." : ".$criteria->parameter." - ";
					// $this->Cell($withCellCriteria , 4, utf8_decode($criteria->abbreviation), 1, 0, 'C', true);
			}
		}

		$this->SetFont('Arial','I',6);
		$this->MultiCell(0, 4, utf8_decode($content), 0, 'L');	
	}

	public function Footer()
	{
		// Posición: a 1,5 cm del final
	    $this->SetY(-20);

		$this->showCriteriaFooter();
	    
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Número de página
	    $this->Cell(0,5,utf8_decode('@tenea - Página ').$this->PageNo(),0,0,'C');
	}

}