<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 29/08/2018
 * Time: 9:35 AM
 */

namespace App\Helpers\Statistics\Percentage;


use App\Helpers\Statistics\Consolidated\JsonConsolidated;
use App\Helpers\Statistics\ParamsStatistics;
use App\Helpers\Statistics\Percentage\Dispatchers\JsonPercentage;
use App\Helpers\Statistics\Percentage\Dispatchers\PdfPercentage;

class DispatcherPercentage
{
    private $response = null;

    public function __construct(ParamsStatistics $params)
    {

        if (isset($params->type_response)) {
            switch ($params->type_response) {

                case 'pdf':
                    $pdf = new PdfPercentage($params);
                    $this->response = $pdf->getProcessedRequest();

                    break;

                case 'excel':
                    $this->response = 'excel';
                    break;

                default:
                    $json = new JsonPercentage($params);
                    $this->response = $json->getProcessedRequest();
            }
        }
    }

    public function getDispatcher()
    {
        return $this->response;
    }
}