<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 9/09/2018
 * Time: 8:43 PM
 */

namespace App\Helpers\Statistics\Reprobated\Dispatchers;


use App\Group;
use App\Helpers\Statistics\ParamsStatistics;
use App\Helpers\Statistics\Reprobated\AbstractReprobated;
use App\Helpers\Statistics\Reprobated\Export\ExportPdf;

class PdfReprobated extends AbstractReprobated
{
    private $export = null;
    private $params = null;
    private $row_by_groups = [];
    private $name_pdf = 'Reprobados ';

    public function __construct(ParamsStatistics $params)
    {
        $this->params = $params;
    }

    public function getProcessedRequest()
    {
        $groups = null;

        if ($this->params->is_filter_all_groups == "true") {
            $groups = Group::getGroupsByGrade($this->params->institution_object->id, $this->params->group_object->grade_id);
            foreach ($groups as $key => $group) {
                $this->params->group_object = $group;
                $this->createVectorGroupForPDF();
            }
            $this->name_pdf = $this->name_pdf.' '.$this->params->group_object->grade_name;
        } else {
            $this->createVectorGroupForPDF();
            $this->name_pdf = $this->name_pdf.' '.$this->params->group_object->name;
        }

        //return $this->row_by_groups;
        $this->export = new ExportPdf('Landscape', 'Letter', $this->row_by_groups, $this->params);
        return $this->export->create($this->name_pdf);
    }

    private function createVectorGroupForPDF()
    {
        $this->params->initConsolidated();
        parent::__construct($this->params);
        $information = (object)array(
            'vector_data' => parent::getProcessedRequest(),
            'name' => $this->params->group_object->name,
            'group_id' => $this->params->group_object->id,
            'grade_name' => $this->params->group_object->grade_name,
            'director_name' => $this->params->group_object->director_name,
            'headquarter_name' =>$this->params->group_object->headquarter_name,
            'working_day_name' => $this->params->group_object->working_day_name,
        );
        //return $information->vector_data;
        array_push($this->row_by_groups, $information);
    }
}