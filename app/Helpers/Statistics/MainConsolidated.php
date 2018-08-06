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

class MainConsolidated
{
    private $params = null;
    private $response = null;

    public function __construct(ParamsStatistics $params)
    {
        $this->params = $params;
    }


    public function create()
    {
        $dispatcher = new DispatcherConsolidated($this->params);
        $this->response = $dispatcher->getDispatcher();

        return $this->response;
    }

}