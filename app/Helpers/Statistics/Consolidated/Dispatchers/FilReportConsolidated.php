<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 11/11/2018
 * Time: 6:45 PM
 */

namespace App\Helpers\Statistics\Consolidated;

use App\Helpers\Statistics\ParamsStatistics;


class FilReportConsolidated extends AbstractConsolidated
{
    private $params = null;
    public function __construct(ParamsStatistics $params)
    {

        $this->params = $params;
        parent::__construct($params);
    }

    public function getProcessedRequest(){
        //parent::getProcessedRequest();
        /*
         *  $params->is_filter_subjects = "false";
        $params->initConsolidated();
         */
        return (array)$this->params;
    }

}