<?php

namespace App\Helpers;

use Illuminate\Http\Request;


use App\Enrollment;
use App\Group;
use App\GroupPensum;
use App\EvaluationPeriod;

class Notebook
{

	private $request = array();
	private $periods = array();
	private $institution = array();
	private $config = array();

	public function __construct(Request $request, $institution)
	{	
		$this->request = $request;
		$this->institution = $institution;

		// Aplicacion la configuracion de las variables
		$this->config();
	}

	public function getPeriods()
	{
		$group = Group::findOrFail($this->request->group);

		$periods = $this->institution->periods()
        ->with('period')
        ->with('schoolyear')
        ->with('state')
        ->with('workingday')
        ->where([
            ['working_day_id', '=', $group->working_day_id],
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
				// 'periodIF' => $this->isIF,
				'onlyAcademic'			=> (isset($this->request['academicCheck'])) ? true : false,
				'decimals'				=> (isset($this->request['decimals'])) ? true : false,
			];
	}

	public function create($enrollment)
	{

		$response = array(
			'tittle'			=>	'INFORME DESCRIPTIVO Y VALORATIVO',
			'tittle_if' 		=> 	'INFORME DE EVALUACIÓN FINAL DEL PROCESO FORMATIVO',
			'current_period' 	=> 	$this->request->period,
			'date' 				=> 	date('Y-m-d'),
			'student' 			=> 	$enrollment->student->toArray(),
			'group'				=>	Group::findOrFail($this->request->group)->toArray(),
			'grade' 			=> 	0,
			'periods'			=> 	array(),
			'config'			=>	$this->config,
			'parameters'		=> 	array()
		);

		

		// Resolvemos las areas, asignaturas y notas de todos los periodos
		foreach ($this->getPeriods() as $key => $periodW) {
			array_push($response['periods'], array(
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

		return $response;
	}

	private function resolveAreas(Enrollment $enrollment, $group_id, $period_id)
	{
		$pensums =  GroupPensum::where('group_id', '=', $this->request->group)->get();
		$response = array();

		foreach ($pensums as $key => $pensum) {
			array_push(
				$response, 
				array(
					'area_id'			=>	$pensum->area_id,
					'area'				=> 	$pensum->area->name,
					'abbreviation'		=>	$pensum->abbreviation,
					'subjects_type_id'	=> 	$pensum->subjectType->name,
					'asignatures'		=>	$this->resolveAsignatures(
						$pensums, $pensum->areas_id, $enrollment, $period_id
					)
				)
			);
		}

		return $response;

	}

	private function resolveAsignatures($pensums, $area_id, Enrollment $enrollment, $period_id)
	{
		$response = array();

		foreach ($pensums as $key => $pensum) {
			
			if($area_id == $pensum->areas_id)
			array_push(
				$response,
				array(
					'asignature_id'	=>	$pensum->asignatures_id,
					'asignature'	=>	$pensum->asignature->name,
					'abbreviation'	=>	$pensum->asignature->abbreviation,
					'ihs'			=>	$pensum->ihs,
					'final_note'	=>	$this->resolveNote(
						$pensum->asignatures_id, $period_id, $enrollment
					),
					'notes'			=>	$this->resolveNotes(
						$pensum->asignatures_id, $period_id, $enrollment
					)
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


	// 
	private function determineRound($value, $roundNumber)
	{
		
		if(!$this->config['decimals'])
			return number_format($value, 0);

		return number_format($value, $roundNumber);
	}
}

