<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 31/07/2018
 * Time: 6:31 PM
 */

namespace App\Helpers\Statistics\Consolidated;



use App\Helpers\Statistics\ParamsStatistics;

class JsonConsolidated extends AbstractConsolidated
{
    public function __construct(ParamsStatistics $params)
    {
        parent::__construct($params);
    }

    public function getProcessedRequest(){
        parent::getProcessedRequest();

        return $this->vectorEnrollments;
    }

}