<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 5/08/2018
 * Time: 3:16 AM
 */

namespace App\Helpers\Evaluation\RelationshipPerformances;


use App\Helpers\Utils\Utils;

class MainRelationship
{
    private $params = null;
    private $response = null;

    public function __construct(ParamsRelationship $params)
    {
        $this->params = $params;
    }

    public function store()
    {
        $this->params->initForStore();
        $this->response = Utils::store_relationship_performances(
            $this->params->notes_parameters_id,
            $this->params->performances_id,
            $this->params->period_id,
            $this->params->group_pensum_id,
            $this->params->config_option_id
        );

        return $this->response;

    }

    public function get(){
        $this->params->initForGet();

        $this->response = Utils::get_relationship_performances(
            $this->params->notes_parameters_id,
            $this->params->group_pensum_id,
            $this->params->period_id,
            $this->params->config_option_id
        );
        return $this->response;
    }

    public function delete(){
        $this->params->initForDelete();

        $this->response = Utils::delete_relationship_performances(
            $this->params->notes_performances_id,
            $this->params->group_performances_id,
            $this->params->config_option_id
        );

        return $this->response;
    }

}