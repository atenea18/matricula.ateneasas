<?php

namespace App\Pdf\Sheet;

/**
* 
*/
use Illuminate\Support\Facades\Storage;
use Codedge\Fpdf\Fpdf\Fpdf;

use App\Traits\utf8Helper;

use App\Group;

class EvaluationSheet extends Fpdf
{

	use utf8Helper;

	public $institution;
	public $parameters;
	public $group;
	public $pensum;
	public $periods;
	public $title = "PLANILLA DE EVALUACIÓN";
 	public $asignatureName;

	// 
	private $_width_mark = 267; //Ancho del marco de la cabecera
	private $_height_mark = 20; //Alto del marco de la cabecera
	private $_with_CE = 70; //Ancho de la celda de estudiantes
	private $_with_C_N_E = 8; //Ancho de la celda novedad (NOV) y estatus (EST)
	private $_with_C_H = 50; //Ancho de la celda donde estan los header (Desempeños)
	private $_width_VG_VRA = 8; //Ancho de las celdas (VRA y VG)
	private $_with_title = 187; //Ancho de la celda del nombre de la institución
	private $_with_CP = 8; // Ancho de la celda de los periodos
	private $_font_tittle = 14; //Tamaño de fuente del nombre de la institución
	private $_font_parameter = 9; //Tamaño de fuente del nombre de la institución
	private $_font_criteria = 6;
	private $_font_student = 7;
	private $_font_header = 8;
	private $_first_place_header = 25;
	private $_second_place_header = 75;
	private $_third_place_header = 100;

	private function _configMargin()
	{
		if($this->DefOrientation == 'P')
		{	
			// 
			$this->asignatureName = substr($this->asignatureName, 0, 22);
			// Anchos
			$this->_width_mark = 0;
			$this->_with_C_N_E = 7;
			$this->_with_C_H = 40;
			$this->_with_CE = 65;
			$this->_width_VG_VRA = 6;
			// Fuentes
			$this->_font_header = 7;
			$this->_font_criteria = 5;
			$this->_font_parameter = 7;

			if(count($this->institution->headquarters) > 1){
		    	$this->_height_mark = 25;
		    }

		    if(count($this->parameters) == 1 && !$this->fieldExistiByName('aee'))
		    {
		    	$this->_with_C_H = 68;
		    	$this->_width_VG_VRA = 7;
		    	$this->_with_C_N_E = 8;

		    	$this->_with_CE = 67;

		    	$this->_font_criteria = 7;
				$this->_font_parameter = 8;

		    }else if(count($this->parameters) == 2 && $this->fieldExistiByName('aee') )
		    {
		    	$this->_with_C_H = 60;
		    	$this->_width_VG_VRA = 7;
		    	$this->_with_C_N_E = 8;
		    	$this->_with_CE = 68;

		    	$this->_font_criteria = 6;
				$this->_font_parameter = 7;

		    }else if(count($this->parameters) == 2 && !$this->fieldExistiByName('aee') )
		    {
		    	$this->_with_C_H = 42.4;
		    	$this->_with_CE = 70;
		    	$this->_font_criteria = 5;
		    }
		    
		    if(count($this->parameters) == 3 && !$this->fieldExistiByName('aee'))
		    {
		    	$this->_with_CE = 56;
		    	$this->_with_C_H = 26;

		    	$this->_font_parameter = 6;
		    	$this->_font_student = 7;
		    }

			$this->_font_tittle = 12;
			$this->_with_title = 120;
			$this->_first_place_header = 20;
			$this->_second_place_header = 50;
			$this->_third_place_header = 90;
		}
		else
		{
			$this->asignatureName = substr($this->asignatureName, 0, 30);

			if(count($this->institution->headquarters) > 1){
		    	$this->_height_mark = 25;
		    }
		    
		    if(count($this->parameters) == 3 && !$this->fieldExistiByName('aee'))
		    {
		    	$this->_with_CE = 70;
		    	$this->_with_C_H = 41;

		    	$this->_font_parameter = 7;
		    	$this->_font_student = 7;
		    }
		    else if(count($this->parameters) == 2 && $this->fieldExistiByName('aee'))
		    {	
		    	$this->_font_parameter = 7;

		    	$this->_with_CE = 90;
		    	$this->_with_C_H = 70;
		    	$this->_with_CP = 10;
		    }
		    else if(count($this->parameters) == 4 && $this->fieldExistiByName('aee'))
		    {
		    	$this->_with_CE = 68;	
		    	$this->_with_C_H = 40;

		    	$this->_font_parameter = 7;
		    	$this->_font_student = 7;
		    	$this->_font_criteria = 5;
		    }
		    else if(count($this->parameters) == 5 && !$this->fieldExistiByName('aee'))
		    {
		    	$this->_with_CE = 60;	
		    	$this->_with_C_H = 40;

		    	$this->_font_parameter = 5;
		    	$this->_font_student = 5;
		    	$this->_font_criteria = 5;
		    }
		    else if(count($this->parameters) == 5 && $this->fieldExistiByName('aee'))
		    {
		    	$this->_with_CE = 68;	
		    	$this->_with_C_H = 40;

		    	$this->_font_parameter = 7;
		    	$this->_font_student = 7;
		    	$this->_font_criteria = 5;
		    }
		}
	}

	public function Header()
	{

		$this->_configMargin();
		
		$group_type = ($this->group instanceof Group ) ? 'GRUPO' : 'SUBGRUPO';
		// Logo
		try
		{	
			if($this->institution->picture != NULL)
				$this->Image(
					Storage::disk('uploads')->url(
						$this->institution->picture
					), 12, 12, 17, 17);
		}catch(\Exception $e){}

		//Marco
	    $this->Cell($this->_width_mark, $this->_height_mark, '', 1,0);
	    $this->Ln(2);

	    // PRIMERA LINEA
	    $this->SetFont('Arial','B', $this->_font_tittle);
	    // Movernos a la dereca
	    $this->Cell(40, 6, '', 0,0);
	    // Título
	    $this->Cell($this->_with_title, 6, ($this->hideTilde($this->institution->name)), 0, 0, 'C');
	    // Movernos a la derecha
	    $this->Cell(40, 6, '', 0,0);
	    // Salto de línea
	    $this->Ln(6);

	    // SEGUNDA LINEA
	    $this->SetFont('Arial','B',9);
	    // $this->Cell(90, 4, '', 0,0);
	    
	    // Título
	    if(count($this->institution->headquarters) > 1){
	    	$this->Cell(0,4, $this->hideTilde($this->group->headquarter->name), 0, 0, 'C');
	    	$this->Ln(4);	
	    }
	    // Movernos a la derecha
	    // $this->Cell(0, 4, '', 0,0);
	    // Salto de línea

	    // TERCERA LINEA
	    // $this->Cell(90, 4, '', 0,0);
	    // Título
	    $this->Cell(0, 4, $this->hideTilde($this->title), 0, 0, 'C');
	    // Movernos a la derecha
	    // $this->Cell(0, 4, '', 0,0);
	    // Salto de línea
	    $this->Ln(4);

	    // CUARTA LINEA
	    $this->SetFont('Arial','',$this->_font_header);
	    // Movernos a la derecha
	    $this->Cell($this->_first_place_header, 4, '', 0,0);
	    $this->Cell($this->_second_place_header, 4, "{$group_type}: ".$this->hideTilde($this->group->name), 0, 0, 'L');
	    // Título
	    $this->Cell($this->_third_place_header,4, ($this->group->director()->first() != null) ? "DIRECTOR DE {$group_type}: ".strtoupper($this->hideTilde($this->group->director()->first()->manager->fullName)) : "DIRECTOR DE {$group_type}: ", 0, 0, 'L');
	    // Movernos a la derecha
	    $this->Cell(0, 4, $this->hideTilde('AÑO LECTIVO ').date('Y'), 0,0);
	    // Salto de línea
	    $this->Ln(4);

	    // QUINTA LINEA
	    // Movernos a la derecha
	    $this->Cell($this->_first_place_header, 4, '', 0,0);
	    $this->Cell($this->_second_place_header, 4, 'ASIGNATURA: '.$this->hideTilde($this->asignatureName), 0, 0, 'L');
	    // Título
	    $this->Cell($this->_third_place_header,4, 'DOCENTE: '.$this->hideTilde($this->pensum['teacher']->fullName), 0, 0, 'L');
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
		// $this->SetFont('Arial','B',9);

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

		$this->SetFont('Arial','B', $this->_font_parameter);

		$this->Cell( (($this->_with_C_N_E * 2) + $this->_with_CE + (count($this->periods) * $this->_with_CP) ), 4, '', 1,0, 'C', true);

		// show Parameter
		foreach($this->parameters as $parameter)
		{
			if(strstr(strtolower($parameter->abbreviation), 'aee') || strstr(strtolower($parameter->abbreviation), 'efp'))
			{
				$this->Cell($this->_width_VG_VRA, 8, substr($parameter->abbreviation, 0,3), 1,0, 'C', true);
			}
			else
			{
				$this->Cell($this->_with_C_H, 4, $this->hideTilde($parameter->parameter), 1, 0, 'C', true);
			}
		}

		$this->Cell($this->_width_VG_VRA, 8, 'VG', 1,0, 'C', true);
        $this->Cell($this->_width_VG_VRA, 8, 'VRA', 1,0, 'C', true);
		$this->Cell($this->_width_VG_VRA, 8, 'Val', 1,0, 'C', true);

		$this->Ln(4);
	}

	private function showCriteria()
	{

		$this->SetFont('Arial','B', $this->_font_criteria);
		// Show Utils
		foreach($this->periods as $period)
		{
			$this->Cell($this->_with_CP, 4, "P{$period}", 1,0, 'C', true);
		}

		// show Criteria
		foreach($this->parameters as $parameter){

			$withCellCriteria = (count($parameter->criterias) > 0) ? (  $this->_with_C_H / (count($parameter->criterias)) ) : $this->_with_C_H;

			if(!strstr(strtolower($parameter->abbreviation), 'aee') && !strstr(strtolower($parameter->abbreviation), 'efp'))
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
						$this->Cell($withCellCriteria , 4, $this->hideTilde($criteria->abbreviation), 1, 0, 'C', true);
				}
			}else{
				$this->Cell($this->_width_VG_VRA, 4, '', 0,0, 'C', false);
			}
		}

	}

	public function create()
	{
		$this->AddPage();
		$this->SetFont('Arial','',$this->_font_student);

		$cont = 1;
		foreach ($this->pensum['students'] as $key => $student) {
			
			if($cont <= 9)
			{
				$this->Cell($this->_with_CE, 4, '0'.($cont++).' '.$this->hideTilde($student['name'])
				, 1,0);	
			}
			else
			{
				$this->Cell($this->_with_CE, 4, ($cont++).' '.$this->hideTilde($student['name'])
				, 1,0);
			}
			
			// Mostramos la novedad
			$this->Cell($this->_with_C_N_E, 4, '', 1,0);
			
			// Mostramos el estado
			$this->Cell($this->_with_C_N_E, 4, $student['state'], 1,0, 'C');

			// Show Utils
			foreach($this->periods as $period)
			{
				foreach($student['periods'] as $periodS)
					if($period == $periodS['period_id'])
						$this->Cell($this->_with_CP, 4, "{$periodS['note']}", 1,0, 'C', false);
			}

			// show field Criteria note
			foreach($this->parameters as $parameter){

				$withCellCriteria = (count($parameter->criterias) > 0) ? (  $this->_with_C_H / (count($parameter->criterias)) ) : $this->_with_C_H;

				if(!strstr(strtolower($parameter->abbreviation), 'aee') && !strstr(strtolower($parameter->abbreviation), 'efp'))
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
					// $this->Cell($withCellCriteria , 4, $this->hideTilde($criteria->abbreviation), 1, 0, 'C', true);
			}
		}

		$this->SetFont('Arial','I',6);
		$this->MultiCell(0, 4, $this->hideTilde($content), 0, 'L');	
	}

	private function fieldExistiByName($name)
	{
		foreach($this->parameters as $key => $parameter)
		{
			if( strstr(strtolower($parameter->abbreviation), strtolower($name)) )
			{
				return true;
			}
		}

		return false;
	}

	public function Footer()
	{
		// Posición: a 1,5 cm del final
	    $this->SetY(-20);

		$this->showCriteriaFooter();
	    
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Número de página
	    $this->Cell(0,5,$this->hideTilde('@tenea - Página ').$this->PageNo(),0,0,'C');
	}

}