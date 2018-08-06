<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 5/08/2018
 * Time: 2:50 AM
 */

namespace App\Helpers\Evaluation\RelationshipPerformances;


class ParamsRelationship
{
    public $config = null;
    public $request = null;
    public $period_id = 0;
    public $performances_id = 0;
    public $group_pensum_id = 0;
    public $notes_parameters_id = 0;

    public $notes_performances_id = 0;
    public $group_performances_id = 0;


    private $config_type_id = 1;
    private $config_type_name = "relation_performances";

    public $config_option_id = 2;
    public $config_option_name = 'column'; // ["row","column"]

    public function __construct($request)
    {
        $this->request = $request;
        $this->config = $request->config;


        foreach ($this->config as $config) {
            if (is_string($config))
                $config = json_decode($config);
            else
                $config = (object)$config;

            if ($this->config_type_id == $config->id) {
                $this->config_option_id = $config->option_id;
                $this->config_option_name = $config->option_name;
                break;
            }
        }
    }

    public function initForStore()
    {
        $this->initStoreAndGet();
        $this->performances_id = $this->request->performances_id;
    }

    public function initForGet()
    {
        $this->initStoreAndGet();
    }


    public function initForDelete()
    {
        if (isset($this->request->notes_performances_id)) {
            $this->notes_performances_id = $this->request->notes_performances_id;
        }

        if (isset($this->request->group_performances_id)) {
            $this->group_performances_id = $this->request->group_performances_id;
        }
    }

    private function initStoreAndGet()
    {
        $this->period_id = $this->request->period_id;
        $this->group_pensum_id = $this->request->group_pensum_id;
        if (isset($this->request->notes_parameters_id)) {
            $this->notes_parameters_id = $this->request->notes_parameters_id;
        }
    }

}