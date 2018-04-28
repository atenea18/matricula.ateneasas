<?php

namespace App\Helpers;

use Illuminate\Http\Request;


use App\Enrollment;
use App\Group;
use App\GroupPensum;
use App\EvaluationPeriod;


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
				// 'includeIF' =>	(isset($this->request['includeIF'])) ? true : false,
				'periodIF' 				=> false,
				'onlyAcademic'			=> (isset($this->request['academicCheck'])) ? true : false,
				'decimals'				=> (isset($this->request['decimals'])) ? true : false,
			];
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
					'valoration'	=>	'wait',
					'teacher'		=>	(!is_null($pensum->teacher)) ? $pensum->teacher : null,
					'final_note'	=>	$this->resolveNote(
						$pensum->asignatures_id, $period_id, $enrollment
					),
					'notes'			=>	$this->resolveNotes(
						$pensum->asignatures_id, $period_id, $enrollment
					),
					'performances'	=>	$this->resolvePerformances(
						$pensum->asignatures_id, $period_id, $enrollment, $pensum->id
					),
				)
			);
		}

		return $response;
	}

	private function resolveNote($asignature_id, $period_id, Enrollment $enrollment)
	{
		$finalNote = EvaluationPeriod::with('noteFinal')
		->where([
			'enrollment_id'		=>	$enrollment->id, 
			'periods_id'		=>	$period_id, 
			'asignatures_id'	=>	$asignature_id
		])
		->get()
		->pluck('noteFinal');

		$response = array();

		foreach($finalNote as $key => $fn)
		{
			$response = [
				'value'			=>	$this->determineRound($fn->value, 1),
				'overcoming'	=>	$fn->overcoming
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
	public function create($enrollment)
	{

		// return $this->resolvePerformances(3, 1, $enrollment);

		$this->noteBook = array(
			'tittle'			=>	'INFORME DESCRIPTIVO Y VALORATIVO',
			'tittle_if' 		=> 	'INFORME DE EVALUACIÓN FINAL DEL PROCESO FORMATIVO',
			'current_period' 	=> 	$this->request->period,
			'date' 				=> 	date('Y-m-d'),
			'student' 			=> 	$enrollment->student->toArray(),
			'group'				=>	$this->group->toArray(),
			'director'			=>	$this->group->director()->first()->manager->toArray(),
			'grade' 			=> 	$this->group->grade->toArray(),
			'headquarter'		=>	$this->group->headquarter->toArray(),
			'institution'		=>	$this->institution->toArray(),
			'periods'			=> 	array(),
			'config'			=>	$this->config,
			'parameters'		=> 	array(),
			'general_obsservation'	=>	$enrollment->observations,
		);

		// Resolvemos las areas, asignaturas y notas de todos los periodos
		foreach ($this->getPeriods() as $key => $periodW) {

			// return (Integer) $this->request->period;
			// return ($this->request->period <= $periodW->period->name);
			if( (Integer) $periodW->period->name <= (Integer) $this->request->period)
			{
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
							$enrollment, $this->request->group, $this->request->period
						)
					)
				);
			}
		}

		// $this->noteBook = $response;

		return $this->noteBook;
	}


	public function setScaleEvaluation($scaleEvaluation)
	{	
		$this->scaleEvaluation = $scaleEvaluation;
	}
}

