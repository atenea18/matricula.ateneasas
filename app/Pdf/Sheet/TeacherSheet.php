<?php

namespace App\Pdf\Sheet;

/**
* 
*/
use Illuminate\Support\Facades\Storage;
use Codedge\Fpdf\Fpdf\Fpdf;

use App\Traits\utf8Helper;

use App\Group;

class TeacherSheet extends Fpdf
{

	use utf8Helper;

	private $institution = array();
	private $headquarter = array();
	private $event = '';
	private $data = array(); 

	private $_width_mark = 0; //Ancho del marco de la cabecera
	private $_with_FC = 6; //Ancho de la primera celda
	private $_with_SC = 90; //Ancho de la segunda celda
	private $_with_TC = 50; //Ancho de la tercera celda
	private $_with_CC = 0; //Ancho de la Cuarta celda

	public function Header()
	{

		$this->configMargin();
		// To be implemented in your own inherited class
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
	    $this->Cell($this->_width_mark,24, '', 1,0);
	    $this->Ln(0);

	    // PRIMERA LINEA
	    $this->SetFont('Arial','B',14);
	    // Movernos a la dereca
	    // $this->Cell(90, 6, '', 0,0);
	    // Título
	    $this->Cell(0, 6, $this->hideTilde($this->institution->name), 0, 0, 'C');
	    // Movernos a la derecha
	    // $this->Cell(0, 6, '', 0,0);
	    // Salto de línea
	    $this->Ln(6);

	    // SEGUNDA LINEA
	    $this->SetFont('Arial','B',9);
	    // $this->Cell(90, 4, '', 0,0);
	    
	    // Título
	    $this->Cell(0,4, strtoupper("SEDE: ".$this->hideTilde($this->headquarter->name)), 0, 0, 'C');
	    $this->Ln(4);

	    // Movernos a la derecha
	    // $this->Cell(0, 4, '', 0,0);
	    // Salto de línea

	    // TERCERA LINEA
	    // $this->Cell(90, 4, '', 0,0);
	    // Título
	    $this->Cell(0, 4, strtoupper("EVENTO: ".$this->hideTilde($this->event)), 0, 0, 'C');
	    // Movernos a la derecha
	    // $this->Cell(0, 4, '', 0,0);
	    // Salto de línea
	    $this->Ln(4);

	    // CUARTA LINEA
	    $this->SetFont('Arial','',8);
	    // Movernos a la derecha
	    $this->Cell(25, 4, '', 0,0);
	    $this->Cell(40, 4, (!is_null($this->group)) ? $this->hideTilde("GRUPO: {$this->group->name}") : "GRUPO: ", 0, 0, 'L');
	    // Título
	    $this->Cell(100,4, '', 0, 0, 'C');
	    // Movernos a la derecha
	    $this->Cell(0, 4, $this->hideTilde('AÑO LECTIVO ').date('Y'), 0,0);
	    // Salto de línea
	    $this->Ln(4);

	    // QUINTA LINEA
	    // Movernos a la derecha
	    $this->Cell(25, 4, '', 0,0);
	    $this->Cell(40, 4, '', 0, 0, 'L');
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
		$this->SetFont('Arial','B', 9);
		$this->SetFillColor(225, 249, 255);

		$this->Cell($this->_with_FC, '5', $this->hideTilde('#'), 1, 0, 'C', true);
		$this->Cell($this->_with_SC, '5', $this->hideTilde('Nombres y Apellidos'), 1, 0, 'C', true);
		$this->Cell($this->_with_TC, '5', $this->hideTilde('N° Identificación'), 1, 0, 'C', true);
		$this->Cell($this->_with_CC, '5', $this->hideTilde('Firma'), 1, 0, 'C', true);

		$this->Ln();
	}

	private function configMargin()
	{
		if(count($this->data) >= 100)
			$this->_with_FC = 7;
	}

	public function setInstitution($institution)
	{
		$this->institution = $institution;
	}

	public function setHeadquarter($headquarter)
	{
		$this->headquarter = $headquarter;
	}

	public function setGroup($group)
	{
		$this->group = $group;
	}

	public function setEvent($event)
	{
		$this->event = $event;
	}

	public function setData($data)
	{
		$this->data = $data;
	}

	public function create()
	{
		$this->AddPage();
		$this->SetFont('Arial','', 9);

		foreach($this->data as $key => $manager)
		{
			$this->Cell($this->_with_FC, '6', ($key < 9) ? "0".($key+1) : ($key+1) , 1, 0, 'C');
			$this->Cell($this->_with_SC, '6', strtoupper($this->hideTilde($manager->name)), 1, 0, 'L');
			$this->Cell($this->_with_TC, '6', $this->hideTilde($manager->identification_number), 1, 0, 'C');
			$this->Cell($this->_with_CC, '6', '', 1, 0, 'C');
			
			$this->Ln();
		}
	}
}