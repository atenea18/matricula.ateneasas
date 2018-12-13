<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 31/07/2018
 * Time: 5:50 PM
 */

namespace App\Helpers\Statistics;


use App\Helpers\Statistics\Consolidated\DispatcherConsolidated;
use App\Helpers\Statistics\Consolidated\JsonConsolidated;
use App\Helpers\Statistics\Percentage\DispatcherPercentage;
use App\Helpers\Statistics\Reprobated\DispatcherReprobated;

class MainStatistics
{
    private $params = null;
    private $response = null;

    public function __construct(ParamsStatistics $params)
    {
        $this->params = $params;
    }


    public function createConsolidate()
    {
        $dispatcher = new DispatcherConsolidated($this->params);
        $this->response = $dispatcher->getDispatcher();

        return $this->response;
    }

    public function createPercentage()
    {
        $dispatcher = new DispatcherPercentage($this->params);
        $this->response = $dispatcher->getDispatcher();

        return $this->response;
    }

    public function createReprobated()
    {
        $dispatcher = new DispatcherReprobated($this->params);
        $this->response = $dispatcher->getDispatcher();

        return $this->response;
    }

}