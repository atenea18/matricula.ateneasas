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
    public $is_filter_all_groups = false;
    public $is_accumulated = false;

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
        /*
         * Inicializar propiedades...
         */
        $this->type_response = $request->type_response;
        $this->period_selected_id = $request->periods_id;
        $this->is_accumulated = $request->is_accumulated;
        $this->is_filter_subjects = $request->is_filter_areas;
        $this->group_object = (object) Group::getGroupsById($request->group_id);
        $this->is_filter_all_groups = $request->is_filter_all_groups;
        $this->institution_object = Institution::findOrfail($request->institution_id);

    }

    public function initConsolidated()
    {

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