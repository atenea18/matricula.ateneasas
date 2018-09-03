<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 29/08/2018
 * Time: 9:29 AM
 */

namespace App\Helpers\Statistics\Percentage\Dispatchers;


use App\Group;
use App\Helpers\Statistics\Percentage\Export\ExportPdf;
use App\Helpers\Statistics\ParamsStatistics;
use App\Helpers\Statistics\Percentage\AbstractPercentage;

class PdfPercentage extends AbstractPercentage
{
    private $export = null;
    private $params = null;
    private $subjects_by_groups = [];
    private $name_pdf = 'Porcentual';

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

        //return $this->subjects_by_groups;
        $this->export = new ExportPdf('Landscape', 'Letter', $this->subjects_by_groups, $this->params);
        return $this->export->create($this->name_pdf);
    }

    private function createVectorGroupForPDF()
    {
        $this->params->initConsolidated();
        parent::__construct($this->params);
        $information = (object)array(
            'subjects' => parent::getProcessedRequest(),
            'scales' => $this->vectorScales,
            'name' => $this->params->group_object->name,
            'group_id' => $this->params->group_object->id,
            'grade_name' => $this->params->group_object->grade_name,
            'director_name' => $this->params->group_object->director_name,
            'headquarter_name' =>$this->params->group_object->headquarter_name,
            'working_day_name' => $this->params->group_object->working_day_name,
        );

        array_push($this->subjects_by_groups, $information);
    }
}