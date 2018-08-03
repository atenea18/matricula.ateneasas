<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 31/07/2018
 * Time: 11:36 PM
 */

namespace App\Helpers\Statistics\Consolidated;


use App\Helpers\Statistics\ParamsStatistics;


class DispatcherConsolidated
{
    private $response = null;

    public function __construct(ParamsStatistics $params)
    {

        if (isset($params->type_response)) {
            switch ($params->type_response) {

                case 'pdf':
                    $pdf = new PdfConsolidated($params);
                    $this->response = $pdf->getProcessedRequest();

                    break;

                case 'excel':
                    $this->response = 'excel';
                    break;

                default:
                    $json = new JsonConsolidated($params);
                    $this->response = $json->getProcessedRequest();
            }
        }
    }

    public function getDispatcher()
    {
        return $this->response;
    }

}