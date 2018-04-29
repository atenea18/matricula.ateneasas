<?php

namespace App\Helpers;

use Illuminate\Http\Request;


use App\Enrollment;
use App\Group;
use App\GroupPensum;
use App\EvaluationPeriod;
use App\NotesFinal;
use App\PeriodWorkingday;

class Notebook
{

	// 
	private $request = array();
	
	private $periods = array();
	private $institution = array();
	private $config = array();
	private $scaleEvaluation = array();
	private $pensums = array();
	private $group = array();
	private $current_period = array();

	// 
	public $noteBook;

	public function __construct(Request $request, $institution)
	{	
		$this->request = $request;
		$this->institution = $institution;

		// Aplicacion la configuracion de las variables
		$this->config();

		// 
		$this->executeAllQueryGlobal();
	}

	private function executeAllQueryGlobal()
	{
		$this->pensums =  GroupPensum::where('group_id', '=', $this->request->group)
							->with('area')
							->with('subjectType')
							->with('teacher.manager')						
							->get()
							->unique('areas_id')
							->values();

		$this->group = Group::findOrFail($this->request->group);

		$this->current_period = PeriodWorkingday::findOrFail($this->request->period);
	}

	private function getPeriods()
	{
		
		$periods = $this->institution->periods()
        ->with('period')
        ->with('schoolyear')
        ->with('state')
        ->with('workingday')
        ->where([
            ['working_day_id', '=', $this->group->working_day_id],
            ['school_year_id', '=', 1]
        ])
        ->get();

		return $periods;
	}

	private function config()
	{
		$this->config = [
				'showTeacher'			=>	(isset($this->request['showTeacher'])) ? true : false,
				'valorationScale'		=>	(isset($this->request['valorationScale'])) ? true : false,
				'showPerformance'		=>	(isset($this->request['showPerformance'])) ? 'indicators' : 'asignature',
				'areasDisabled'			=>	(isset($this->request['areasDisabled'])) ? true : false,
				'doubleFace'			=>	(isset($this->request['doubleFace'])) ? true : false,
				'generalReportPeriod'	=>	(isset($this->request['generalReportPeriod'])) ? true : false,
				'showFaces'				=>	(isset($this->request['showFaces'])) ? true : false,
				'combinedEvaluation'	=>	(isset($this->request['CombinedEvaluation'])) ? true : false,
				'NumberValoration'		=>	(isset($this->request['NumberValoration'])) ? true : false,
				'tableDetail'			=>	(isset($this->request['tableDetail'])) ? true : false,
				'performanceRating'		=>	(isset($this->request['performanceRating'])) ? true : false,
				'includeIF' 			=>	(isset($this->request['includeIF'])) ? true : false,
				'periodIF' 				=> false,
				'onlyAcademic'			=> (isset($this->request['academicCheck'])) ? true : false,
				'decimals'				=> (isset($this->request['decimals'])) ? true : false,
			];
	}

	public function create($enrollment)
	{

		// return $this->resolvePerformances(3, 1, $enrollment);

		$this->noteBook = array(
			'tittle'				=>	'INFORME DESCRIPTIVO Y VALORATIVO',
			'tittle_if' 			=> 	'INFORME DE EVALUACIÓN FINAL DEL PROCESO FORMATIVO',
			'current_period' 		=> 	$this->current_period,
			'date' 					=> 	date('Y-m-d'),
			'student' 				=> 	$enrollment->student,
			'group'					=>	$this->group->toArray(),
			'director'				=>	$this->group->director()->first()->manager,
			'grade' 				=> 	$this->group->grade->toArray(),
			'headquarter'			=>	$this->group->headquarter->toArray(),
			'institution'			=>	$this->institution->toArray(),
			'periods'				=> 	array(),
			'config'				=>	$this->config,
			'parameters'			=> 	array(),
			'general_obsservation'	=>	$enrollment->observations()->where('period_working_day_id', '=', $this->request->period)->get(),
			'valueScale'			=>	$this->scaleEvaluation,
		);

		// Resolvemos las areas, asignaturas y notas de todos los periodos
		foreach ($this->getPeriods() as $key => $periodW) {

			// return (Integer) $this->request->period;
			// return ($this->request->period <= $periodW->period->name);
			// if( (Integer) $periodW->period->name <= (Integer) $this->request->period)
			// {
				array_push($this->noteBook['periods'], array(
						'code_working_day_periods'	=> 	$periodW->code_working_day_periods,
						'percent'					=>	$periodW->percent,
						'start_date'				=>	$periodW->start_date,
						'end_date'					=>	$periodW->end_date,
						'working_day_id'			=>	$periodW->working_day_id,
						'periods_id'				=>	$periodW->periods_id,
						'periods_state_id'			=>	$periodW->periods_state_id,
						'school_year_id'			=>	$periodW->school_year_id,
						'areas'						=>	$this->resolveAreas(
							$enrollment, $this->request->group, $periodW->periods_id
						),
						'average'					=>	$this->resolveAveragePeriod($enrollment, 1, $periodW->periods_id),
					)
				);
			// }
		}

		// $this->noteBook = $response;

		return $this->noteBook;
	}

	private function resolveAveragePeriod(Enrollment $enrollment, $school_year_id, $period)
	{
		$averages = $this->averageStudents(
			NotesFinal::getAverageByGroup($this->group->id, $school_year_id, $this->institution->id, $period)
		);

		foreach ($averages as $key => $average) {
			
			if($average['id'] == $enrollment->id)
			{
				return [
					'average'	=>	$average['average'],
					'tav'		=>	$average['tav'],
					'position'	=>	$average['rating'],
				];
			}
		}

		return 	[];
	}

	private function resolveAreas(Enrollment $enrollment, $group_id, $period_id)
	{
		
		$response = array();

		foreach ($this->pensums as $key => $pensum) {
			array_push(
				$response, 
				array(
					'pensum_id'			=>	$pensum->id,
					'area_id'			=>	$pensum->areas_id,
					'area'				=> 	$pensum->area->name,
					'abbreviation'		=>	$pensum->abbreviation,
					'subjects_type_id'	=> 	$pensum->subjectType->name,
					'note'				=> 	0,
					'valoration'		=>	'espera',
					'asignatures'		=>	$this->resolveAsignatures(
						$pensum->areas_id, $enrollment, $period_id
					)
				)
			);
		}

		return $response;

	}

	private function resolveAsignatures($area_id, Enrollment $enrollment, $period_id)
	{
		$response = array();

		foreach ($this->pensums as $key => $pensum) {
			
			if($area_id == $pensum->areas_id)
			array_push(
				$response,
				array(
					'asignature_id'	=>	$pensum->asignatures_id,
					'asignature'	=>	$pensum->asignature->name,
					'abbreviation'	=>	$pensum->asignature->abbreviation,
					'ihs'			=>	$pensum->ihs,
					'teacher'		=>	(!is_null($pensum->teacher)) ? $pensum->teacher : null,
					'final_note'	=>	$this->resolveNote(
						$pensum->asignatures_id, $period_id, $enrollment, $pensum->id
					),
					'notes'			=>	$this->resolveNotes(
						$pensum->asignatures_id, $period_id, $enrollment
					),
					'indicators'	=>	$this->resolvePerformances(
						$pensum->asignatures_id, $period_id, $enrollment, $pensum->id
					),
				)
			);
		}

		return $response;
	}

	private function resolveNote($asignature_id, $period_id, Enrollment $enrollment, $pensum_id)
	{
		$EvalP = EvaluationPeriod::with('noteFinal')
		->with('noAttendances')
		->where([
			'enrollment_id'		=>	$enrollment->id, 
			'periods_id'		=>	$period_id, 
			'asignatures_id'	=>	$asignature_id
		])
		->get();

		$response = array();

		foreach($EvalP as $key => $ev)
		{
			$note = $this->determineRound($ev->noteFinal->value, 1);
			$response = [
				'valoration'	=>	$this->getScaleByNote($note),
				'value'			=>	$note,
				'overcoming'	=>	$ev->noteFinal->overcoming,
				'performances'	=>	$this->resolveIndicators(
					$asignature_id, $period_id, $enrollment, $pensum_id, $note
				),
				'noAttendances'	=>	$ev->noAttendances->sum('quantity'),
			];
		}

		return $response;
	}

	private function resolveNotes($asignature_id, $period_id, Enrollment $enrollment)
	{
		$notes = EvaluationPeriod::with('notes')
		->where([
			'enrollment_id'		=>	$enrollment->id, 
			'periods_id'		=>	$period_id, 
			'asignatures_id'	=>	$asignature_id
		])
		->get()
		->pluck('notes')
		->collapse();

		$response = array();

		foreach ($notes as $key => $note) {
			array_push(
				$response, 
				[
					'value'			=>	$this->determineRound($note->value, 1),
					'overcoming'	=>	$note->overcoming,
				]
			);
		}

		return $response;
	}

	private function resolvePerformances($asignature_id, $period_id, Enrollment $enrollment, $pensum_id)
	{
		$notes = EvaluationPeriod::with([
			'notes.noteParameter.notePerformances' => function($pensum) use ($pensum_id){
				$pensum->where('group_pensum_id', '=', $pensum_id)
				->with('performance.message')
				->get()
				->pluck('performance.message');
			}])
		->where([
			'enrollment_id'		=>	$enrollment->id, 
			'periods_id'		=>	$period_id, 
			'asignatures_id'	=>	$asignature_id
		])
		->get()
		->pluck('notes')
		->collapse();

		// return $notes;
		$response = array();

		foreach ($notes as $key => $note) {
			
			foreach($note->noteParameter->notePerformances as $keyNP => $notePerformances)
			{
				array_push(
					$response, 
					[
						'expression'		=>	$this->getScaleByNote($note->value),
						'message'			=>	$notePerformances->performance->message->name,
						'reinforcement'		=>	$notePerformances->performance->message->reinforcement,
						'reinforcement'		=>	$notePerformances->performance->message->reinforcement,
						'recommendation'	=>	$notePerformances->performance->message->recommendation,
					]
				);
			}
		}

		return $response;
	}

	private function resolveIndicators($asignature_id, $period_id, Enrollment $enrollment, $pensum_id, $noteAsig)
	{
		$notes = EvaluationPeriod::with([
			'notes.noteParameter.notePerformances' => function($pensum) use ($pensum_id){
				$pensum->where('group_pensum_id', '=', $pensum_id)
				->with('performance.message')
				->get()
				->pluck('performance.message');
			}])
		->where([
			'enrollment_id'		=>	$enrollment->id, 
			'periods_id'		=>	$period_id, 
			'asignatures_id'	=>	$asignature_id
		])
		->get()
		->pluck('notes')
		->collapse();

		// return $notes;
		$response = array();

		foreach ($notes as $key => $note) {
			
			foreach($note->noteParameter->notePerformances as $keyNP => $notePerformances)
			{
				array_push(
					$response, 
					[
						'expression'		=>	$this->getScaleByNote($noteAsig),
						'message'			=>	$notePerformances->performance->message->name,
						'reinforcement'		=>	$notePerformances->performance->message->reinforcement,
						'reinforcement'		=>	$notePerformances->performance->message->reinforcement,
						'recommendation'	=>	$notePerformances->performance->message->recommendation,
					]
				);
			}
		}

		return $response;
	}

	// 
	private function determineRound($value, $roundNumber)
	{
		
		if(!$this->config['decimals'])
			return number_format($value, 0);

		return number_format($value, $roundNumber);
	}

	private function getScaleByNote($note)
	{
		foreach ($this->scaleEvaluation as $key => $scale) {
			
			if($note >= $scale->rank_start && $note <= $scale->rank_end)
			{
				return [
					'name'				=>	$scale->name,
					'abbreviation'		=>	$scale->abbreviation,
					'word_expression'	=>	$scale->wordExpresion->name,
				];
			}
		}
	}

	// Funciones Públicas
	

	public function setScaleEvaluation($scaleEvaluation)
	{	
		$this->scaleEvaluation = $scaleEvaluation;
	}

	public function averageStudents($arryStudentAverage){
	
		#
		$count=0;

		#Array donde se va almacenar objetos de estudiantes de arrayStudentAverage, pero con una estructra un poco modificada
		$vectorOfStudents = array();

		#En este vector se va a guardar el número de asignaras evaluada por cada estudiante
		$vectorNumberAsignatures = array();

		foreach ($arryStudentAverage as $key => $value) {
			$vectorStudent = array(
				'id' => $value['enrollment_id'], 				
				'average' => $value['average'],
				'tav' => $value['tav']
			);

			#Se guarda la nueva estructura en el vector por cada estudiante
			$vectorOfStudents[$count] = $vectorStudent;
			
			# Se guarda el tav de asignatura del estudiante i o count.. 
			$vectorNumberAsignatures[$count]= $value['tav'];

			$count++;
		}

		#Obtengo y almaceno el número maximo de asignaturas evaluadas
		$numberMaxOfAsignatures = $this->getMaxValue($vectorNumberAsignatures);

		
		#Este es un nuevo vector donde se va a guardar los mismo estudiantes pero con el promedio levemente modificado
		$vectorOfStudentsAux = array();
		foreach ($vectorOfStudents as $value) {

			#Esta formula da como resultado un promedio auxiliar, 
			#Nos soluciona el problema de aquellos estudiantes que tienen un promedio alto pero con menor 
			#asignaturas evaluadas
			$averageAux = (($value['average']*$value['tav'])/$numberMaxOfAsignatures);

			$vectorStudent = array(
				'id' => $value['id'], 
				'averageAux' => $averageAux,
				'average' => $value['average'],
				'tav' => $value['tav']
			);
			#usamos el id de estudiante como el indice del vector
			$vectorOfStudentsAux[$value['id']] = $vectorStudent;			
		}


		$vectorOfStudentsAux = $this->orderMultiDimensionalArray($vectorOfStudentsAux, 'averageAux', true);
		return $this->generateRating($vectorOfStudentsAux);

		// $vectorOfStudentsAux = self::orderMultiDimensionalArray($vectorOfStudentsAux, 'averageAux', true);
		// return self::generateRating($vectorOfStudentsAux);
	}

	public function orderMultiDimensionalArray ($toOrderArray, $field, $inverse = false) {
		$position = array();
		$newRow = array();
		foreach ($toOrderArray as $key => $row) {
			$position[$key]  = $row[$field];
			$newRow[$key] = $row;
		}
		if ($inverse) {
			arsort($position);
		}
		else {
			asort($position);
		}
		$returnArray = array();
		foreach ($position as $key => $pos) {     
			$returnArray[] = $newRow[$key];
		}
		return $returnArray;
	}

	private function generateRating($vectorOfStudentsAux){
		#variable con la que voy asignar el puesto de cada estudiante	
		$countAux=1;

		#promedio auxiliar que comienza en cero
		$averageAux=0;
		$vectorRating = array();

		#Vamos a recorrer el vector auxiliar de estudiante, ya esta ordenado segun al promdedio modificado
		foreach ($vectorOfStudentsAux as $key => $value) {

			#Si es mayor
			if($value['averageAux']>$averageAux){
				$vectorOfStudent = array(
					'id' => $value['id'], 
					'rating' => $countAux , 					
					'average' => $value['average'],
					'tav' => $value['tav'] 
				);
				$averageAux = $value['averageAux'];
				$vectorRating[$value['id']]= $vectorOfStudent;
				$countAux++;
			}
			#Si es igual
			if($value['averageAux']==$averageAux){
				$vectorOfStudent = array(
					'id' => $value['id'], 
					'rating' => $countAux-1, 
					'average' => $value['average'],
					'tav' => $value['tav'] 
				);
				$averageAux=$value['averageAux'];
				$vectorRating[$value['id']] = $vectorOfStudent;
			}
			#Si es menor
			if($value['averageAux']<$averageAux){
				$vectorOfStudent = array(
					'id' => $value['id'],
					'rating' => $countAux, 					
					'average' => $value['average'],
					'tav' => $value['tav'] 					
				);
				$averageAux=$value['averageAux'];
				$vectorRating[$value['id']] = $vectorOfStudent;
				$countAux++;
			}
			
		}

		return $vectorRating;
	}

	public function getMaxValue($array = array())
	{
		$max = 0;

		foreach ($array as $key => $value) {
			
			if($value > $max)
				$max = $value;
		}

		return $max;
	}
}

