<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 27/08/2018
 * Time: 9:30 PM
 */
namespace App\Helpers\Statistics\Percentage\Dispatchers;

use App\Helpers\Statistics\ParamsStatistics;
use App\Helpers\Statistics\Percentage\AbstractPercentage;

class JsonPercentage extends AbstractPercentage
{
    public function __construct(ParamsStatistics $params)
    {
        parent::__construct($params);
    }

    public function getProcessedRequest(){
        return parent::getProcessedRequest();
        //return $this->vectorEnrollments;
        //return 'hola';
    }

}