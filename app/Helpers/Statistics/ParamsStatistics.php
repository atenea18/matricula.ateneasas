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
use App\ScaleEvaluation;
use App\Workingday;
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
    public $vectorScales = [];


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
        $this->institution_object = $request->institution;

        $this->vectorScales =  ScaleEvaluation::getScaleByInstitution($request->institution->id);;

    }

    public function initConsolidated()
    {

        //Notas de todos los periodos, por áreas o asignaturas, segun el filtro
        $this->vectorNotes = \Utils::query_get_notes_final(
            $this->institution_object->id,
            $this->group_object->id,
            $this->is_filter_subjects
        );

        //Asignaturas o Áreas segun el filtro
        $this->vectorSubjects = \Utils::query_get_subjects(
            $this->group_object->id,
            $this->is_filter_subjects
        );

        //Escala de valoración
        $this->minimum_scale_object = ScaleEvaluation::getMinScale($this->institution_object);
        $this->middle_point = $this->minimum_scale_object->rank_end += 0.1;

        //Estudiantes
        $this->vectorEnrollments = Group::enrollmentsByGroup($this->institution_object->id,$this->group_object->id);

        //Periodos
        $this->vectorPeriods = Workingday::getPeriodsByGroup($this->institution_object->id,$this->group_object->working_day_id);
        $this->num_of_periods = count($this->vectorPeriods);

    }


}