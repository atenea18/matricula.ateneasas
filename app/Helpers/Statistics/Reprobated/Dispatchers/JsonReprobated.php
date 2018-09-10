<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 8/09/2018
 * Time: 1:12 PM
 */

namespace App\Helpers\Statistics\Reprobated\Dispatchers;


use App\Helpers\Statistics\ParamsStatistics;
use App\Helpers\Statistics\Reprobated\AbstractReprobated;

class JsonReprobated extends AbstractReprobated
{
    public function __construct(ParamsStatistics $params)
    {
        parent::__construct($params);

    }

    public function getProcessedRequest(){
        return parent::getProcessedRequest();
    }
}