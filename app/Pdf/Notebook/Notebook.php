<?php

namespace App\Pdf\Notebook;

/**
* 
*/
use Illuminate\Support\Facades\Storage;
use Codedge\Fpdf\Fpdf\Fpdf;

/**
* 
*/
class Notebook extends Fpdf
{

	private $data = array();
	
	private $_h_c = 4;

	function header()
	{
		// Logo
		if($this->data['institution']['picture'] != NULL)
			$this->Image(
				Storage::disk('uploads')->url(
					$this->data['institution']['picture']
				), 12, 12, 17, 17);

		//Marco
	    $this->Cell(0, 24, '', 1,0);
	    $this->Ln(0);

	    // NOMBRE DE LA INSTITUCIÓN
	    $this->SetFont('Arial','B',12);
	    $this->Cell(0, 6, utf8_decode($this->data['institution']['name']), 0, 0, 'C');
	    $this->Ln(6);

	    $this->SetFont('Arial','B',9);
	    // NOMBRE DE LA SEDE
	    if(!empty($this->data['headquarter'])):
		    $this->Cell(0,4, 'SEDE: '.strtoupper(($this->data['headquarter']['name'])), 0, 0, 'C');
		    
	    endif;

	    $this->Ln(4);

	    // TITULO DEL PDF
	    $this->Cell(0, 4, strtoupper($this->data['tittle']), 0, 0, 'C');
	    $this->Ln();

	    // NOMBRE DEL GRUPO
	    $this->SetFont('Arial','',8);
	    $this->Cell(20, 4, '', 0,0);
	    $this->Cell(90, 4, 'GRUPO: '.$this->data['group']['name'], 0, 0, 'L');

	    // DIRECTOR DE GRUPO
	     $this->Cell(0,4, utf8_decode('DIR. DE GRUPO: '.
	     	    	$this->data['director']['last_name']." ".
	     	    	$this->data['director']['name']), 0, 0, 'L');
	    $this->Ln();

	    // NOMBRE DEL ESTUDIANTE
	    $this->Cell(20, 4, '', 0,0);
	    $this->Cell(90, 4, 'ESTUDIANTE: '.utf8_decode(
	    	$this->data['student']['last_name']." ".
	    	$this->data['student']['name']
	    ), 0, 0, 'L');

	    // FECHA
	    $this->Cell(0, 4, 'FECHA: '.$this->data['date'], 0,0, 'L');
	    // Salto de línea
	    $this->Ln(8);

	    $this->subHeader();
	}

	private function subHeader()
	{

		

		// if($this->data['config']['periodIF']):
		// 	$this->Cell(140, $this->_h_c, utf8_decode($this->gradeBook['tittle_if']), 1,0, 'L'); 
		// else:
			$this->Cell(140, $this->_h_c, $this->data['tittle'].utf8_decode(' PERIODO '.$this->data["current_period"].' - AÑO LECTIVO ').date('Y'), 1,0, 'L'); 
		// endif;
		

		$this->Cell(10, $this->_h_c, 'IHS', 1,0, 'C');

		$this->Cell(17, $this->_h_c, 'VAL', 1,0, 'C');
		$this->Cell(0, $this->_h_c, utf8_decode('DESEMPEÑO'), 1,0, 'C');

		$this->Ln($this->_h_c+4);

		// Marco
		$this->Cell(0, 215, '', 1,0);
	    $this->Ln(0);
	}

	public function setData($data = array())
	{
		$this->data = $data;
	}

	public function create()
	{
		// AÑADIMOS UNA PAGINA EN BLANCO
		$this->addPage();

		// RECOREMOS LOS PERIODOS
		foreach($this->data['periods'] as $periodKey => $period):

			// PREGUNTAMOS SI EL PERIODO RECORRIDO ES IGUAL AL PERIOD SOLICITADO
			if($period['periods_id'] == $this->data['current_period']):

				// MOSTRAMOS LAS AREAS
				$this->showAreas($period['areas']);
				$this->Cell(0, $this->_h_c, '', 'T',0, 'L');
			endif;

		endforeach;
	}

	private function showAreas($areas = array())
	{
		// RECORREMOS LAS AREAS
		foreach($areas as $areaKey => $area):

			// FONDO PARA LAS CELDAS DE LAS AREAS
			$this->SetFillColor(230,230,230);
			$this->SetFont('Arial','B',8);

			// VERIFICAMOS LA CALIFICACIÓN DEL AREA
			if($area['note'] == 0):
				// PREGUNTAMOS SI EL PERIODO IF ESTA ACTIVO
				if(!$this->data['config']['periodIF']):
					// PREGUNTAMOS SI LAS AREAS NO SE DESACTIVAN
					if(!$this->data['config']['areasDisabled']):

						$this->Cell(150, $this->_h_c, utf8_decode($area['area']), 'TBL',0, 'L', true);
						$this->Cell(17, $this->_h_c, $area['note'], 'TB',0, 'C', true);
						$this->Cell(0, $this->_h_c, strtoupper($area['valoration']), 'TBR', 0, 'C', true);
					
					else:

						$this->Cell(0, $this->_h_c, utf8_decode($area['area']), 1,0, 'L', true);

					endif;
					$this->Ln();
				else:
					// foreach($this->averageAreaFinalReport as $report):
					// 	if($area['id_area'] == $report['id_area'] && $report['id_student'] == $this->gradeBook['student']['id']):
					// 		// PREGUNTAMOS SI LAS AREAS NO SE DESACTIVAN
					// 		if(!$this->gradeBook['config']['areasDisabled']):
					// 			$this->Cell(150, $this->_h_c, ($report['area'])." ".$report['type'], 'TBL',0, 'L', true);
					// 			$this->Cell(17, $this->_h_c, $report['note'], 'TB',0, 'C', true);
					// 			$this->Cell(0, $this->_h_c, strtoupper($report['valoration']), 'TBR', 0, 'C', true);
					// 		else:
					// 			$this->Cell(0, $this->_h_c, utf8_decode($report['area']), 1,0, 'L', true);
					// 		endif;
					// 	$this->Ln();
					// 	endif;
					// endforeach;
				endif;
				// RECORREMOS LAS ASIGNATURAS
				$this->showAsignature($area['asignatures']);
			endif;

			
		endforeach;
	}

	/***/
	private function showAsignature($asignatures = array())
	{	
		// 
		foreach($asignatures as $keyAsignature => $asignature):

			if($this->determineShowValoration($asignature)):
				
				// if($asignature['nota'] > 0):

					if($this->data['config']['periodIF']):
						// foreach($this->finalReportList as $report):
						// 	if($this->gradeBook['student']['id']==$report['id_student'] && $report['id_asignature'] == $asignature['asignature_id']):
						// 		$def = $report['nota'];

						// 		$this->showValoration($report, $asignature['ihs'], true);
						// 	endif;
						// endforeach;
					else:
						// MOSTRAMOS LA VALORACIÓN
						$this->showValoration($asignature, $asignature['ihs'], false);
					endif;

					if(!$this->data['config']['periodIF']):
						// MOSTRAMOS LOS DESEMPEÑOS (LOGROS)
						// $this->SetFont('Arial','',8);
						$this->showPerformance($asignature['performances']);

						// MOSTRAMOS LAS OBSERVACIONES
						// $this->showObservationsByAsignature($asignature);
					endif;

					// MOSTRAMOS AL DOCENTE
					if($this->data['config']['showTeacher']):
						$this->showTeacher($asignature['teacher']);
					endif;

				// endif;

				
			endif;
			
		endforeach;
	}

	/**
	*
	*
	*/
	private function determineShowValoration($asignature=array())
	{
		foreach($this->data['periods'] as $keyPeriod => $period)
		{


			foreach($period['areas'] as $keyAreas => $area)
			{
				foreach($area['asignatures'] as $keyAsignature => $asignaturee)
				{

					if(isset($asignaturee['final_note']['value']))
					{

						if($asignature['asignature_id'] == $asignaturee['asignature_id'] && $asignaturee['final_note']['value'] > 0 )
						{

							return true;
						}
					}

				}
			}
		}

		return false;
	}

	/**
	*
	*
	*/
	private function showValoration($asignature, $ihs = 0, $utf8_encode = false)
	{	
		$this->SetFont('Arial','B',8);

		$pahtImage = env('APP_URL')."/img/";
		$height = 0;
		$note = 0;
		$valoration = '';

		if($this->data['config']['NumberValoration']):
			$note = (isset($asignature['final_note']['value'])) ? $asignature['final_note']['value'] : 0;
			$valoration = strtoupper($asignature['valoration']);
		else: 
			$note = '';
			$valoration = '';
		endif;

		$ihs = ($ihs == 0) ? '' : $ihs;
		
		if($this->data['config']['showFaces'] == true):
			$height = 11;
		else:
			$height = $this->_h_c;
		endif;

		if(!$utf8_encode)
			$this->Cell(140, $height, utf8_decode($asignature['asignature']), 'L',0, 'L');
		else
			$this->Cell(140, $height, ($asignature['asignature']), 'L',0, 'L');

		$this->Cell(10, $height, $ihs, 0,0, 'C');

		$this->Cell(17, $height, $note, 0,0, 'C');

		if($this->data['config']['showFaces'] == true):

			$this->Image($pahtImage.strtolower($asignature['valoration']).'.jpg', 185, $this->GetY()+1, 9, 9, 'JPG');
			$this->Cell(0, $height, '', 'R', 0, 'C');

		else:

			$this->Cell(0, $this->_h_c, $valoration, 'R', 0, 'C');
		endif;

		$this->Ln($height);
	}

	/**
	*
	*
	*/
	private function showPerformance($performances = array())
	{
		if($this->data['config']['showPerformance'] == 'asignature')
		{
			foreach($performances as $key => $performance):
					
				$this->determineCell(
					utf8_decode('   * '.strtoupper($performance['expression']['word_expression'].", ".$performance['message'])
					), 
				'LR');
			
			endforeach;
		}
	}

	/**
	*
	*
	*/
	private function determineCell($data, $border)
	{	
		$this->SetFont('Arial','',8);

		if(strlen($data) > 100 && strlen($data) > 0)
			$this->MultiCell(0, $this->_h_c, strip_tags($data), $border, 'L');
		else if(strlen($data) > 0)
		{
			$this->Cell(0, $this->_h_c, strip_tags($data), $border,0, 'L');
			$this->Ln(4);
		}
	}

	/**
	*
	*
	*/
	private function showTeacher($teacher)
	{	
		$this->SetFont('Arial','B',8);
		$this->Cell(0, $this->_h_c,'DOCENTE: '. utf8_decode($teacher->manager->fullNameInverse), 'LR',0,'R');

		$this->Ln($this->_h_c);
	}

	function footer()
	{
		// Posición: a 1,5 cm del final
	    $this->SetY(-20);
	    
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Número de página
	    $this->Cell(0,5,utf8_decode('@tenea - Página ').$this->PageNo(),0,0,'C');
	}
}