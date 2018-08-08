<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 1/08/2018
 * Time: 12:31 AM
 */

namespace App\Helpers\Statistics\Consolidated;


use App\Group;
use App\Helpers\Statistics\ParamsStatistics;
use Illuminate\Support\Facades\App;
use setasign\Fpdi\Fpdi;

class PdfConsolidated extends AbstractConsolidated
{
    private $params = null;
    private $enrollments_by_groups = [];

    public function __construct(ParamsStatistics $params)
    {
        $this->params = $params;
        //parent::__construct($params);
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

        //return $this->createPdf();
        return $this->enrollments_by_groups;

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

    private function createPdf(){
        $fpdi = new Fpdi();
        $fpdi->SetLineWidth(.01);
        foreach ($this->enrollments_by_groups as $key => $vector){
            $fpdi->AddPage('Landscape', 'Letter',0);
            $fpdi->SetFont('Times', '', 10);
            $fpdi->SetTextColor(0, 0, 0);
            foreach ($vector->enrollments as $enrollment){
                $fpdi->Cell(50,5,$fpdi->GetX().'  '.$fpdi->GetPageWidth(),1, 1, 'L');
            }

        }

        $fpdi->Output('D', 'consolidated-'.$this->params->group_object->name.'.pdf',true);
    }

}