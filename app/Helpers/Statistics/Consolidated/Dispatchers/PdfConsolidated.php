<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 1/08/2018
 * Time: 12:31 AM
 */

namespace App\Helpers\Statistics\Consolidated;


use App\Group;
use App\Helpers\Statistics\Consolidated\Export\ExportPdf;
use App\Helpers\Statistics\ParamsStatistics;
use Illuminate\Support\Facades\App;
use setasign\Fpdi\Fpdi;

class PdfConsolidated extends AbstractConsolidated
{
    private $export = null;
    private $params = null;
    private $enrollments_by_groups = [];

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

        } else {
            $this->createVectorGroupForPDF();
        }

        $this->export = new ExportPdf('Landscape', 'Letter', $this->enrollments_by_groups, $this->params);
        return $this->export->createConsolidated();

    }

    private function createVectorGroupForPDF()
    {
        $this->params->initConsolidated();
        parent::__construct($this->params);
        parent::getProcessedRequest();
        $information = (object)array(
            'group_id' => $this->params->group_object->id,
            'name' => $this->params->group_object->name,
            'subjects' => $this->params->vectorSubjects,
            'enrollments' => $this->vectorEnrollments
        );

        array_push($this->enrollments_by_groups, $information);
    }


}