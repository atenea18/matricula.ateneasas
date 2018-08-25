<?php
namespace App\Helpers;
/**
 * 
 */

use App\Traits\ConvertFormant;

use App\Group;
use App\Subgroup;
use App\EvaluationPeriod;

class EvaluationSheetHelper
{
	use ConvertFormant;
	// 
	public $request = array();
	public $typeGroup = 'group';
	// 
	public $group_id;
	public $group;
	public $periods;
	public $current_period;
	public $enrollments;

	public $pensums;

	public function __construct($group_id, $request, $periods)
	{
		$this->request = $request;
		$this->periods = $periods;
		$this->current_period = $request->period_id;
		$this->group = ($request->group_type == "group") ? Group::findOrFail($group_id) : Subgroup::findOrFail($group_id) ;

		$this->enrollments = $this->group->enrollments()
        ->with('student')
        ->with('student.state')
        ->where('school_year_id', '=', 1)
        ->get()
        ->sortBy('student.last_name');
	}

	public function getGroup()
	{
		return $this->group;
	}

	public function getDirector()
	{
		try
		{
			return (!is_null($this->group->director)) ? $this->group->director->first()->manager : '' ;
		}catch(\Exception $e)
		{

		}
	}

	public function getPensums()
	{
		return array(
			'current_period'=>	$this->current_period,
			'group'			=>	$this->group,
			'headquarter'	=>	$this->group->headquarter,
			'director'		=>	$this->getDirector(),
			'pensums'		=>	$this->resolvePensum(),
		);
	}

	public function getByAsignature($asignature_id)
	{
		return array(
			'current_period'=>	$this->current_period,
			'group'			=>	$this->group,
			'headquarter'	=>	$this->group->headquarter,
			'director'		=>	$this->getDirector(),
			'pensums'		=>	$this->resolvePensum($asignature_id, true),
		);
	}

	private function resolvePensum($asignature_id = 0, $compare = false)
	{
		$pensums = $this->group
		->pensums()
		->with('teacher.manager')
		->with('asignature')
		->where('schoolyear_id', '=', 1)
		->get();

		$response = array();

		if($compare)
		{
			foreach($pensums as $pensum)
			{
				if($asignature_id == $pensum->asignatures_id )
				{
					array_push($response, [
						'id'		=>	$pensum->id,
						'asignature_id'	=>	$pensum->asignatures_id,
						'asignature'	=>	$pensum->asignature->name,
						'teacher'		=>	(!is_null($pensum->teacher)) ? $pensum->teacher->manager : '',
						'students'		=>	$this->resolveStudents($asignature_id),
					]);
				}
			}
		}
		else
		{
			foreach($pensums as $pensum)
			{
				$asignatureId = ($asignature_id == 0) ? $pensum->asignatures_id : $asignature_id ;
				array_push($response, [
					'id'		=>	$pensum->id,
					'asignature_id'	=>	$pensum->asignatures_id,
					'asignature'	=>	$pensum->asignature->name,
					'teacher'		=>	(!is_null($pensum->teacher)) ? $pensum->teacher->manager : '',
					'students'		=>	$this->resolveStudents($asignatureId),
				]);
			}
		}

		return $response;
	}

	private function resolveStudents($asignature_id)
	{
       	$response = array();

       	foreach($this->enrollments as $enrollment)
       	{
       		array_push($response, [
       			'enrollment_id'	=>	$enrollment->id,
       			'name'	=>	$enrollment->student->fullNameInverse,
       			'state'	=>	$enrollment->student->state->state,
       			'periods'	=>	$this->resolvePeriods($enrollment->id, $asignature_id),
       		]);
       	}

       	return $response;
	}

	private function resolvePeriods($enrollment_id, $asignature_id)
	{
		// try {
			$response = array();
			$periods_used = array();

			foreach($this->periods as $period)
			{
				$evalP = EvaluationPeriod::with('noteFinal')
				->where([
					'enrollment_id'		=>	$enrollment_id, 
					'periods_id'		=>	$period->periods_id, 
					'asignatures_id'	=>	$asignature_id
				])
				->first();

				$note = 0;
				$overcoming = 0;

				if(!is_null($evalP))
				{
					try {
						$note = $evalP->noteFinal->value;
						$overcoming = $evalP->noteFinal->overcoming;
					} catch (\Exception $e) {
					}
				}
				if(!in_array($period->periods_id, $periods_used)){
					
					array_push($periods_used, $period->periods_id);

					array_push($response, [
						'period_id'		=>	$period->periods_id,
						'percent'		=>	$period->percent,
						'note'			=>	($note == 0) ? '' : $this->determineRound($note, 1, true),
						'overcoming'	=>	$overcoming,
					]);
				}
			}

			return $response;	
		// } catch (\Exception $e) {
			
		// }
	}
}