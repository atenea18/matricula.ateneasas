<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 1/08/2018
 * Time: 12:31 AM
 */

namespace App\Helpers\Statistics\Consolidated;


use App\Helpers\Statistics\ParamsStatistics;
use Illuminate\Support\Facades\App;

class PdfConsolidated extends AbstractConsolidated
{
    public function __construct(ParamsStatistics $params)
    {
        parent::__construct($params);
    }

    public function getProcessedRequest(){
        parent::$this->getProcessedRequest();

        $this->response = 'Estoy programando un poquito mejor';
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->response)->setPaper('a4', 'landscape')->setWarnings(false)->save('consolidado.pdf');
        return $pdf->stream();
    }

}