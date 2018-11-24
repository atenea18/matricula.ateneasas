<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinalReportAsignature extends Model
{
    protected $table = 'final_report_asignature';

    static public function getEnrollmentsByGroup($group_id)
    {
        return FinalReportAsignature::select(
            'enrollment.id as enrollment_id', 'final_report_asignature.id as final_report_asignature_id',
            'final_report_asignature.asignatures_id', 'final_report_asignature.value',
            'final_report_asignature.report', 'final_report_asignature.overcoming',
            'asignatures.name')
            ->join('enrollment', 'enrollment.id', '=', 'final_report_asignature.enrollment_id')
            ->join('group_assignment', 'enrollment.id', '=', 'group_assignment.enrollment_id')
            ->join('asignatures','asignatures.id', '=','final_report_asignature.asignatures_id')
            ->where('group_assignment.group_id', '=', $group_id)
            ->get();
    }

    static public function getEnrollmentsByGroupAsignatures($group_id, $asignature_id){
        return FinalReportAsignature::select(
            'enrollment.id as enrollment_id', 'final_report_asignature.id as final_report_asignature_id',
            'final_report_asignature.asignatures_id', 'final_report_asignature.value',
            'final_report_asignature.report', 'final_report_asignature.overcoming',
            'asignatures.name')
            ->join('enrollment', 'enrollment.id', '=', 'final_report_asignature.enrollment_id')
            ->join('group_assignment', 'enrollment.id', '=', 'group_assignment.enrollment_id')
            ->join('asignatures','asignatures.id', '=','final_report_asignature.asignatures_id')
            ->where('group_assignment.group_id', '=', $group_id)
            ->where('final_report_asignature.asignatures_id', '=', $asignature_id)
            ->get();
    }
}
