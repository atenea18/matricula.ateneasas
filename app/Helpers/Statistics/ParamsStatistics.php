<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 31/07/2018
 * Time: 8:58 PM
 */

namespace App\Helpers\Statistics;


use App\Group;
use App\Institution;
use http\Env\Request;

class ParamsStatistics
{
    private $request = null;

    public $subjects_url = '';
    public $type_response = '';
    public $consolidated_url = '';
    public $is_filter_subjects = false;

    public $middle_point = 0;
    public $num_of_periods = 0;
    public $period_selected_id = 1;

    public $group_object = null;
    public $institution_object = null;
    public $minimum_scale_object = null;

    public $vectorNotes = [];
    public $vectorPeriods = [];
    public $vectorSubjects = [];
    public $vectorEnrollments = [];


    public function __construct($request)
    {
        $this->request = $request;
    }

    public function initConsolidated()
    {
        /*
         * Inicializar propiedades...
         */

        $this->type_response = $this->request->type_response;
        $this->period_selected_id = $this->request->periods_id;
        $this->group_object = Group::findOrfail($this->request->group_id);
        $this->institution_object = Institution::findOrfail($this->request->institution_id);
        $this->is_filter_subjects = $this->request->is_filter_areas;


        /*
         * Dependencias...
         */

        $this->vectorPeriods = \Utils::get_periods_by_group(
            $this->institution_object->id,
            $this->group_object->working_day_id
        );

        $this->vectorNotes = \Utils::query_get_notes_final(
            $this->institution_object->id,
            $this->group_object->id,
            $this->is_filter_subjects
        );

        $this->vectorSubjects = \Utils::query_get_subjects(
            $this->group_object->id,
            $this->is_filter_subjects
        );
        $this->minimum_scale_object = \Utils::get_min_scale(
            $this->institution_object
        );

        $this->vectorEnrollments = \Utils::get_enrollments_by_group(
            $this->institution_object->id,
            $this->group_object->id
        );

        $this->middle_point = $this->minimum_scale_object->rank_end += 0.1;

        $this->num_of_periods = count($this->vectorPeriods);

    }


}