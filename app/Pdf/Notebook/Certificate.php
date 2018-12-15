<?php

namespace App\Pdf\Notebook;

/**
 *
 */
use Illuminate\Support\Facades\Storage;
use Codedge\Fpdf\Fpdf\Fpdf;

use App\Traits\utf8Helper;

/**
 *
 */
class Certificate extends Fpdf
{

    use utf8Helper;

    public $institution;
    public $enrollment;
    public $firms;

    private $_h_c = 4;

    public $grados = array(
		1  => 'MATERNO',
		2  => 'PRE-JARDIN',
		3  => 'JARDIN',
		4  => 'TRANSICIÓN',
		6  => 'PRIMERO (1°)',
		7  => 'SEGUNDO (2°)',
		8  => 'TERCERO (3°)',
		9  => 'CUARTO (4°)',
		10  => 'QUINTO (5°)',
		21 => 'SEXTO (6°)',
		12 => 'SÉPTIMO (7°)',
		13 => 'OCTAVO (8°)',
		14 => 'NOVENO (9°)',
		15 => 'DÉCIMO (10°)',
		16 => 'UNDÉCIMO (11°)',
	);

    function Header()
	{

		try
		{
			if($this->institution->picture != NULL)
				$this->Image(
					Storage::disk('uploads')->url(
						$this->institution->picture
					), 15, 15, 17, 17);
		}catch(Exception $e){}


		// PRIMERA LINEA
	    $this->SetFont('Arial','B',12);
	    // Título
	    $this->Cell(0, 6, utf8_decode($this->institution->name), 0, 0, 'C');
	    $this->Ln(10);

	    // Título
	    $this->SetFont('Arial','B',9);
	    


	    // Título
	    $this->Cell(0, 4, "CERTIFICADO DE ESTUDIO", 0, 0, 'C');

		$this->Ln(10);
	}

	public function create($data = array())
	{
		$this->addPage();

		$this->SetFont('Arial','',10);

		$this->showHeader();

		$this->showBody();

		// MOSTRAMOS EL CUADRO ACUMULATIVO
		$this->showCombineEvaluation($data);

		// 
		$this->showDate();

		$this->showFirms();
	}

	private function showHeader()
	{
		$this->SetFont('Arial','B',10);
		if($this->firms->header != ''):
			$this->MultiCell(0, 4, utf8_decode($this->firms->header), 0, 'C');
			// $this->Cell(0, 6, $this->certificado[0]['encabezado'], 0, 1, 'C');
		endif;
		// $this->Ln(5);
		// $this->Cell(0, 6, $name, 0, 1, 'C');
		$this->Ln(2);
	}

	private function showBody()
	{
		$this->SetFont('Arial','',11);

		$body = "Que {$this->enrollment->student->fullNameInverse} con {$this->enrollment->student->identification->identificationFull}, {$this->getNovelty()}  en este plantel el grado {$this->getGroup()}, durante el año lectivo {$this->enrollment->schoolYear->year} de acuerdo a la ley de Educación, Ley 115 de Febrero 8 de 1994, y su decreto Reglamentario 1860 de Agosto 3 de 1994, Evidenciando los siguientes procesos:";

		$this->MultiCell(0, 4, utf8_decode($body), 0, 'L');
	}

	private function getGroup()
	{
		$group = $this->enrollment->group()->first();

		return "{$this->grados[$group->grade_id]} en la jornada de la {$group->workingday->name} en la {$group->grade->academicLevel->name}";
	}

	private function getNovelty()
	{
		$msg = 'curso y ';

		switch ($this->enrollment->finalReport->news_id) {
			case 39:
				$msg.=' APROBO';
				break;
			case 45:
				$msg.=' REPROBO';
				break;
			case 47:
				$msg.=' RETIRO';
				break;
			case 10:
				$msg.=' DESERTO';
				break;
			default:
				# code...
				break;
		}

		return $msg;
	}


	private function showCombineEvaluation($data = array())
	{
		$this->Ln($this->_h_c * 2);

		$this->SetFont('Arial', 'B', 8);

        $this->Cell(0, $this->_h_c, $this->hideTilde('VALORACIONES ACUMULADAS DURANTE EL AÑO LECTIVO'), 1, 1, 'C');

        $this->Cell(140, $this->_h_c, 'AREAS / ASIGNATURAS', 'LB',0, 'C');
		$this->Cell(7, $this->_h_c, 'IHS', 1,0, 'C');
		$this->Cell(22, $this->_h_c, utf8_decode('VALORACIÓN'), 1,0, 'C');
		$this->Cell(0, $this->_h_c, utf8_decode('DESEMPEÑO'), 'TBR',1, 'C');

		$this->showAreas($data);
	}

	private function showAreas($data = array())
	{	
		
		foreach($data as $area):
				$this->SetFillColor(230,230,230);
				$this->SetFont('Arial','B',8);
				// PREGUNTAMOS SI LAS AREAS NO SE DESACTIVAN
				// if($this->areasEnabled):
					$this->Cell(140, $this->_h_c, utf8_decode($area['name']), 'TBL',0, 'L', true);
					$this->Cell(7, $this->_h_c, "", 1,0, 'L', true);
					$this->Cell(22, $this->_h_c, $area['value'], 1,0, 'C', true);
					$this->Cell(0, $this->_h_c, strtoupper(utf8_decode($area['scale'])), 1, 0, 'C', true);
				// else:
				// 	$this->Cell(0, $this->h_c, ($area['area']), 1,0, 'L', true);
				// endif;
			$this->Ln();
			$this->showAsignature($area['asignature']);
		endforeach;
	}

	private function showAsignature($asignatures=array())
	{
		// $this->SetFillColor(230,230,230);
		$this->SetFont('Arial','',8);
		foreach($asignatures as $asignature):
				// PREGUNTAMOS SI LAS AREAS NO SE DESACTIVAN
				// if($this->asignatureEnabled):
					$this->Cell(140, $this->_h_c, utf8_decode($asignature['name']), 'TBL',0, 'L');
					$this->Cell(7, $this->_h_c, $asignature['ihs'], 1,0, 'C');
					$this->Cell(22, $this->_h_c, $asignature['value'], 1,0, 'C');
					$this->Cell(0, $this->_h_c, strtoupper(utf8_decode($asignature['scale'])), 'TBR', 0, 'C');
				// else:
				// 	$this->Cell(0, $this->h_c, ($asignature['name']), 1,0, 'L');
				// endif;
				$this->Ln();
		endforeach;
	}

	public function showDate()
	{
		$this->Ln(8);
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		$this->SetFont('Arial','',9);
		// $ciudad = $this->certificado[0]['ciudad_expedicion'];
		$msg = "Dado en {$this->firms->place_expedition_document} a los ".date('d')." dias del mes de ".$meses[date('n')-1]." de ".date('Y');
		$this->Cell(70, $this->_h_c, $msg, 0,0, 'L');
	}

	private function showFirms()
	{
		$this->Ln(25);
		$this->SetFont('Arial','B',9);
		
		if($this->firms->rector_firm != ''):
			$this->Cell(70, $this->_h_c, strtoupper(utf8_decode($this->firms->rector_firm)), 'T',0, 'L');
			$this->Cell(50, $this->_h_c, "", 0, 0, 'C');
		endif;
		
		if($this->firms->secretary_firm != '')
			$this->Cell(50, $this->_h_c, strtoupper(utf8_decode($this->firms->secretary_firm)), 'T', 0, 'L');
		$this->Ln(4);
		$this->SetFont('Arial','',9);
		// 
		if($this->firms->rector_firm != '' && $this->firms->rector_number_document != ''):
			$this->Cell(70, $this->_h_c, utf8_decode($this->firms->rector_number_document." de ".$this->firms->rector_place_expedition), 0,0, 'L');
			$this->Cell(50, $this->_h_c, "", 0, 0, 'C');
		endif;
		if($this->firms->secretary_firm != '' && $this->firms->secretary_number_document != '')
			$this->Cell(50, $this->_h_c, utf8_decode($this->firms->secretary_number_document." de ".$this->firms->secretary_place_expedition), 0, 0, 'L');
		$this->Ln(4);
		// 
		if($this->firms->rector_firm != ''):
			$this->Cell(70, $this->_h_c, 'Rector(a)', 0,0, 'L');
			$this->Cell(50, $this->_h_c, "", 0, 0, 'C');
		endif;
		
		if($this->firms->secretary_firm != '')
			$this->Cell(50, $this->_h_c, 'Secretario(a)', 0, 1, 'L');	
	}

	function footer()
	{
		// Posición: a 1,5 cm del final
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Número de página
	    $this->Cell(0, 4,utf8_decode('Ágora - Página ').$this->PageNo(),0,0,'C');
	}
}