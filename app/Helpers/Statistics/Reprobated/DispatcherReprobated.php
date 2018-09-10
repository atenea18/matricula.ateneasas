<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 8/09/2018
 * Time: 1:09 PM
 */

namespace App\Helpers\Statistics\Reprobated;


use App\Helpers\Statistics\ParamsStatistics;
use App\Helpers\Statistics\Reprobated\Dispatchers\JsonReprobated;
use App\Helpers\Statistics\Reprobated\Dispatchers\PdfReprobated;

class DispatcherReprobated
{
    private $response = null;

    public function __construct(ParamsStatistics $params)
    {

        if (isset($params->type_response)) {
            switch ($params->type_response) {

                case 'pdf':
                    $pdf = new PdfReprobated($params);
                    $this->response = $pdf->getProcessedRequest();

                    break;

                case 'excel':
                    $this->response = 'excel';
                    break;

                default:
                    $json = new JsonReprobated($params);
                    $this->response = $json->getProcessedRequest();
            }
        }
    }

    public function getDispatcher()
    {
        return $this->response;
    }
}