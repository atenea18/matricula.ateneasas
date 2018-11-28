<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

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
            ->join('asignatures', 'asignatures.id', '=', 'final_report_asignature.asignatures_id')
            ->where('group_assignment.group_id', '=', $group_id)
            ->get();
    }

    static public function getEnrollmentsByGroupAsignatures($group_id, $asignature_id)
    {
        return FinalReportAsignature::select(
            'enrollment.id as enrollment_id', 'final_report_asignature.id as final_report_asignature_id',
            'final_report_asignature.asignatures_id', 'final_report_asignature.value',
            'final_report_asignature.report', 'final_report_asignature.overcoming',
            'asignatures.name')
            ->join('enrollment', 'enrollment.id', '=', 'final_report_asignature.enrollment_id')
            ->join('group_assignment', 'enrollment.id', '=', 'group_assignment.enrollment_id')
            ->join('asignatures', 'asignatures.id', '=', 'final_report_asignature.asignatures_id')
            ->where('group_assignment.group_id', '=', $group_id)
            ->where('final_report_asignature.asignatures_id', '=', $asignature_id)
            ->get();
    }

    static public function getEnrollmentsAreasByGroup($group_id)
    {
        return DB::select(DB::raw("
        SELECT result.enrollment_id, result.last_name, result.name, result.name_subjects,
        SUM(result.percent) percent,                
        ROUND(IF((SUM(result.percent) = 100),
        SUM((result.percent/100) * result.value),
        SUM(result.value)/SUM((result.value>0))), 2)
                        'value',
                        0 'overcoming',
        SUM(result.tav) tav, result.areas_id as 'asignatures_id',
        result.final_report_asignature_id
        from

        (SELECT
		enrollment.id as 'enrollment_id', 

		IF( final_report_asignature.value >= IFNULL(final_report_asignature.overcoming,0),
			final_report_asignature.value, final_report_asignature.overcoming) AS 'value', 
		final_report_asignature.value AS overcoming,
		final_report_asignature.id as 'final_report_asignature_id', 
		areas.id as 'areas_id', student.last_name as 'last_name', student.name as 'name', areas.`name` as 'name_subjects',
		asignatures.`name` as 'name_a',
		group_pensum.percent as 'percent', 
		(final_report_asignature.value>0) as 'tav'
        FROM final_report_asignature
        INNER JOIN enrollment ON enrollment.id = final_report_asignature.enrollment_id
        INNER JOIN student ON student.id = enrollment.student_id
        INNER JOIN institution ON institution.id = enrollment.institution_id
        INNER JOIN schoolyears ON schoolyears.id = enrollment.school_year_id
        INNER JOIN group_assignment ON group_assignment.enrollment_id = enrollment.id
        INNER JOIN `group` ON `group`.id = group_assignment.group_id
        INNER JOIN headquarter ON headquarter.id = group.headquarter_id AND headquarter.institution_id =  institution.id 
        INNER JOIN group_pensum ON group_pensum.group_id = `group`.id
        INNER JOIN areas ON areas.id = group_pensum.areas_id
        INNER JOIN asignatures ON asignatures.id = group_pensum.asignatures_id 
            and  final_report_asignature.asignatures_id = asignatures.id
            and  final_report_asignature.asignatures_id = group_pensum.asignatures_id 
        WHERE `group`.id = {$group_id} AND
        schoolyears.id = 1  
        GROUP BY enrollment.id, asignatures.id
        ) as result
        GROUP BY result.enrollment_id, result.areas_id
        ORDER BY result.last_name, result.name
                "));
    }
}
