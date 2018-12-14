<?php
namespace App\Helpers;

use App\Traits\ConvertFormant;

use App\Enrollment;
use App\SubGroupPensum;
use App\Area;

class Certificate
{
	use ConvertFormant;

	public $enrollment;

	private $pensums_areas = array();
    private $pensums_asignatures = array();

    private $averageAreas = array();

    private $finalReportAsignatures = array();
    private $scaleEvaluation = array();

    private $config = array();

	function __construct($enrollment, $averageAreas = array(), $scaleEvaluation, $request = array())
	{
		$this->enrollment = Enrollment::findOrFail($enrollment);
		$this->averageAreas = $averageAreas;
		$this->scaleEvaluation = $scaleEvaluation;
		// 
		$this->finalReportAsignatures = $this->enrollment->finalReportAsignatures;

		$this->config($request);
	}

	public function config($data = array() )
	{
		$this->config = [
			'decimals' => (isset($data['decimals'])) ? true : false,
		];
	}

	public function create()
	{

		$this->getPensumsAreas();
		
		$this->getPensumsAsignatgures();

		return $this->resolveAreas();
	}

	private function getPensumsAreas()
    {

        $groupEnrollment = $this->enrollment->group()->first();
        $subgroupEnrollment = $this->enrollment->subgroups()->first();

        $groupPensum = array();
        $SubGroupPensum = array();

        if (!is_null($groupEnrollment)) {
            $groupPensum = $groupEnrollment->pensums()
                //->where('subjects_type_id', '!=', 2)
                ->where('areas_id', '!=', 62)
                ->with('area')
                // ->with('subjectType')
                // ->with('teacher.manager')
                ->orderBy('order', 'asc')
                ->get()
                ->unique('areas_id')
                ->values();

            foreach ($groupPensum as $key => $pensum) {
                array_push($this->pensums_areas, $pensum);
            }
        }

        if (!is_null($subgroupEnrollment)) {
            
            $subgroupPensum = SubGroupPensum::where('sub_group_id', '=', $subgroupEnrollment->id)
                ->with('area')
                // ->with('subjectType')
                ->orderBy('order', 'asc')
                ->get()
                ->unique('areas_id')
                ->values();

            foreach ($subgroupPensum as $key => $pensum) {
                array_push($this->pensums_areas, $pensum);
            }
            
        }
        //dd($this->pensums_areas);
        return $this->pensums_areas;
    }

    private function getPensumsAsignatgures()
    {
        $groupEnrollment = $this->enrollment->group()->first();
        $subgroupEnrollment = $this->enrollment->subgroups()->first();

        $groupPensum = array();
        $SubGroupPensum = array();

        if (!is_null($groupEnrollment)) {
            $groupPensum = $groupEnrollment->pensums()
                ->where('subjects_type_id', '!=', 2)
                // ->with('area')
                ->with('asignature')
                // ->with('teacher.manager')
                ->orderBy('order', 'asc')
                ->get();

            foreach ($groupPensum as $key => $pensum) {
                array_push($this->pensums_asignatures, $pensum);
            }
        }

        if (!is_null($subgroupEnrollment)) {
            $subgroupPensum = SubGroupPensum::where('sub_group_id', '=', $subgroupEnrollment->id)
                // ->with('area')
                ->with('asignature')
                ->orderBy('order', 'asc')
                ->get();

            foreach ($subgroupPensum as $key => $pensum) {
                array_push($this->pensums_asignatures, $pensum);
            }
        }
        return $this->pensums_asignatures;
    }

    private function resolveAreas()
    {
    	$response = array();

    	foreach($this->pensums_areas as $area)
    		foreach($this->averageAreas as $AverageArea)
    			if($AverageArea->enrollment_id == $this->enrollment->id && $AverageArea->area_id == $area->areas_id)
		    		array_push($response, [
		    			'name'	=>	$area->area->name,
		    			'id'	=>	$area->areas_id,
		    			'value' =>	$this->determineRound($AverageArea->value, 1, $this->config['decimals']),
		    			'scale'	=>	$this->getScaleEvaluation($this->determineRound($AverageArea->value, 1, $this->config['decimals'])),
		    			'tav'	=>	$AverageArea->tav,
		    			'asignature'	=>	$this->resolveAsignatures($area->area)
		    		]);
    			

    	return $response;
    }

    private function resolveAsignatures(Area $area)
    {
    	$response = array();

    	foreach ($this->pensums_asignatures as $key => $asignaturep) {
    		foreach($this->finalReportAsignatures as $report)
	    		if($area->id == $asignaturep->areas_id && $report->asignatures_id == $asignaturep->asignature->id)
	    			array_push($response, [
	    				'id'	=>	$asignaturep->asignature->id,
	    				'name'	=>	$asignaturep->asignature->name,
	    				'ihs'	=>	$asignaturep->ihs,
	    				'value'	=>	$this->determineRound($report->value, 1, $this->config['decimals']),
	    				'scale'	=>	$this->getScaleEvaluation($this->determineRound($report->value, 1, $this->config['decimals'])),
	    				'overcoming'	=>	$report->overcoming
	    			]);
    	}

    	return $response;
    }

    private function getScaleEvaluation($note)
    {
    	foreach($this->scaleEvaluation as $scale)
    		if($note >= $scale->rank_start && $note <= $scale->rank_end)
    			return $scale->name;

    	return null;
    }
}