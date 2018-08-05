<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 31/07/2018
 * Time: 7:07 PM
 */

namespace App\Helpers\Utils;


use App\Group;
use App\GroupPensum;
use App\NotesFinal;
use App\NotesParametersPerformances;
use App\ScaleEvaluation;
use App\Workingday;

class Utils
{
    public static function get_periods_by_group($institution_id, $working_day_id)
    {
        return Workingday::getPeriodsByGroup($institution_id, $working_day_id);
    }

    /**
     * Obtiene notas finales por asignatura o áreas
     */
    public static function query_get_notes_final($institution_id, $group_id, $is_filter)
    {
        if ($is_filter == "true") {
            return NotesFinal::getNotesFilterByAreas($institution_id, $group_id);
        }
        if ($is_filter != "true") {
            return NotesFinal::getNotesFilterByAsignatures($institution_id, $group_id);
        }
    }


    public static function query_get_subjects($group_id, $is_filter)
    {
        if ($is_filter == "true") {
            return GroupPensum::getAreasByGroupX($group_id);
        }
        if ($is_filter != "true") {
            return GroupPensum::getAsignaturesByGroupX($group_id);
        }
    }

    public static function get_min_scale($institution_id)
    {
        return ScaleEvaluation::getMinScale($institution_id);
    }

    public static function get_enrollments_by_group($institution_id, $group_id)
    {
        return Group::enrollmentsByGroup($institution_id, $group_id);
    }

    public static function store_relationship_performances(
        $notes_parameters_id,
        $performances_id,
        $periods_id,
        $group_pensum_id,
        $config_option_id)
    {
        if ($config_option_id == 1) {
            return 'Hola';
        }
        if ($config_option_id == 2) {
            return NotesParametersPerformances::insertRelation(
                $notes_parameters_id,
                $performances_id,
                $periods_id,
                $group_pensum_id
            );
        }
    }
}