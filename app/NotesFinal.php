<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

use App\ScaleEvaluation;
use App\Institution;


class NotesFinal extends Model
{
    protected $table = 'notes_final';

    protected $fillable = [
        'code_notes_final', 'value', 'overcoming', 'evaluation_periods_id'
    ];

    public function evaluationPeriod()
    {
        return $this->belongsTo(EvaluationPeriod::class, 'evaluation_periods_id');
    }

    public function updateOvercoming(Institution $institution)
    {

        $minScale = ScaleEvaluation::getMinScale($institution);
    	$hihgScale = ScaleEvaluation::getHighScale($institution);

    	if($this->overcoming >= $minScale->rank_start && $this->overcoming <= $hihgScale->rank_end)
    	{
    		if($this->overcoming > $this->value){

    			$noteAux = $this->overcoming;
    			$this->overcoming = $this->value;
    			$this->value = $noteAux;
    		}

    		$this->update();

    		return [
    			'state'		=>	true,
    			'code'		=>	200,
    			'message' 	=> 'Superacion registrada con exito',
    		];
    	}

    	return [
    		'state'		=>	false,
    		'code'		=>	422,
    		'message' 	=> 'Rango no permitido',
    	];

    }

    public static function getConsolidate($data)
    {
        if ($data->is_subgroup == "false") {

            $enrollments = Group::enrollmentsByGroup($data->institution_id, $data->group_id);

            $notes_final = DB::table('notes_final')
                ->select('enrollment.id as enrollment_id', 'notes_final.value', 'notes_final.overcoming',
                    'notes_final.id as notes_final_id', 'evaluation_periods.asignatures_id', 'evaluation_periods.periods_id',
                    'evaluation_periods.id as evaluation_periods_id')
                ->join('evaluation_periods', 'evaluation_periods.id', '=', 'notes_final.evaluation_periods_id')
                ->join('enrollment', 'enrollment.id', '=', 'evaluation_periods.enrollment_id')
                ->join('grade', 'enrollment.grade_id', '=', 'grade.id')
                ->join('group_assignment', 'enrollment.id', '=', 'group_assignment.enrollment_id')
                ->join('group', 'group_assignment.group_id', '=', 'group.id')
                ->join('group_pensum', 'group_pensum.group_id', '=', 'group.id')
                ->join('institution', 'enrollment.institution_id', '=', 'institution.id')
                ->join('headquarter', 'institution.id', '=', 'headquarter.institution_id')
                ->join('schoolyears', 'enrollment.school_year_id', 'schoolyears.id')
                ->whereColumn(
                    [
                        ['headquarter.id', '=', 'group.headquarter_id'],
                        ['group.grade_id', '=', 'grade.id'],
                        ['group_pensum.asignatures_id', '=', 'evaluation_periods.asignatures_id']
                    ]
                )
                ->where('group.id', '=', $data->group_id)
                ->where('institution.id', '=', $data->institution_id)
                ->where('schoolyears.id', '=', '1')
                ->where('evaluation_periods.periods_id', '=', $data->period_id)
                ->get();
        } else {

            $enrollments = Subgroup::enrollmentsBySubGroup($data->institution_id, $data->group_id);

            $notes_final = DB::table('notes_final')
                ->select('enrollment.id as enrollment_id', 'notes_final.value', 'notes_final.overcoming',
                    'notes_final.id as notes_final_id', 'evaluation_periods.asignatures_id', 'evaluation_periods.periods_id',
                    'evaluation_periods.id as evaluation_periods_id')
                ->join('evaluation_periods', 'evaluation_periods.id', '=', 'notes_final.evaluation_periods_id')
                ->join('enrollment', 'enrollment.id', '=', 'evaluation_periods.enrollment_id')
                ->join('grade', 'enrollment.grade_id', '=', 'grade.id')
                ->join('sub_group_assignments', 'enrollment.id', '=', 'sub_group_assignments.enrollment_id')
                ->join('sub_group', 'sub_group_assignments.subgroup_id', '=', 'sub_group.id')
                ->join('sub_group_pensum', 'sub_group_pensum.sub_group_id', '=', 'sub_group.id')
                ->join('institution', 'enrollment.institution_id', '=', 'institution.id')
                ->join('headquarter', 'institution.id', '=', 'headquarter.institution_id')
                ->join('schoolyears', 'enrollment.school_year_id', 'schoolyears.id')
                ->whereColumn(
                    [
                        ['headquarter.id', '=', 'sub_group.headquarter_id'],
                        ['sub_group.grade_id', '=', 'grade.id'],
                        ['sub_group_pensum.asignatures_id', '=', 'evaluation_periods.asignatures_id']
                    ]
                )
                ->where('sub_group.id', '=', $data->group_id)
                ->where('institution.id', '=', $data->institution_id)
                ->where('schoolyears.id', '=', '1')
                ->where('evaluation_periods.periods_id', '=', $data->period_id)
                ->get();


        }

        $collection = [];

        foreach ($enrollments as $key => $enrollment) {

            $collection_notes_final = [];
            foreach ($notes_final as $keyNotes => $note) {
                if ($enrollment->id == $note->enrollment_id) {
                    array_push($collection_notes_final, $note);
                    unset($notes_final[$keyNotes]);
                }
            }

            $enrollment->notes_final = $collection_notes_final;
            array_push($collection, $enrollment);
        }


        return $collection;
    }

    public static function getAverageByGroup($group_id, $school_year_id, $institution_id, $periods_id)
    {
        return self::select('enrollment.id AS enrollment_id', DB::raw('CONCAT(student.last_name," ",student.name) as name_student'), DB::raw('ROUND(SUM(notes_final.`value`)/SUM(notes_final.`value`>0), 1) AS average'), DB::raw('SUM(notes_final.`value`>0) AS tav'))
            ->join('evaluation_periods', 'evaluation_periods.id', '=', 'notes_final.evaluation_periods_id')
            ->join('enrollment', 'enrollment.id', '=', 'evaluation_periods.enrollment_id')
            ->join('student', 'student.id', '=', 'enrollment.student_id')
            ->join('institution', 'institution.id', '=', 'enrollment.institution_id')
            ->join('schoolyears', 'schoolyears.id', '=', 'enrollment.school_year_id')
            ->join('group_assignment', 'group_assignment.enrollment_id', '=', 'enrollment.id')
            ->join('group', 'group.id', '=', 'group_assignment.group_id')
            ->join('headquarter', function ($join) {
                $join->on('headquarter.id', '=', 'group.headquarter_id')
                    ->on('headquarter.institution_id', '=', 'institution.id');
            })
            ->join('group_pensum', function ($join) {
                $join->on('group_pensum.group_id', '=', 'group.id')
                    ->on('group_pensum.asignatures_id', '=', 'evaluation_periods.asignatures_id');
            })
            ->join('areas', 'areas.id', '=', 'group_pensum.areas_id')
            ->join('asignatures', 'asignatures.id', '=', 'group_pensum.asignatures_id')
            ->where([
                ['group.id', '=', $group_id],
                ['institution.id', '=', $institution_id],
                ['schoolyears.id', '=', $school_year_id],
                ['evaluation_periods.periods_id', '=', $periods_id]
            ])
            ->groupBy('enrollment.id')
            ->orderBy('average', 'desc')
            ->get();
    }

    public static function getAverageGroupPensum($group_id, $institution_id, $school_year_id, $period_id)
    {
        return DB::select(DB::raw("SELECT result.enrollment_id, result.last_name, result.name, result.name_areas, SUM(result.percent) percent,
            ROUND(IF((SUM(result.percent) = 100), 
                SUM((result.percent/100) * result.value), 
                SUM(result.value)/SUM((result.value>0))), 2) average, 
            SUM(result.tav) tav,
            result.areas_id
            from
            (SELECT 
            enrollment.id as 'enrollment_id',
            student.last_name as 'last_name', student.name as 'name', areas.`name` as 'name_areas', group_pensum.percent as 'percent',
            notes_final.`value` as 'value', (notes_final.`value`>0) tav, areas.id as 'areas_id'
            FROM notes_final
            INNER JOIN evaluation_periods ON evaluation_periods.id = notes_final.evaluation_periods_id
            INNER JOIN enrollment ON enrollment.id = evaluation_periods.enrollment_id
            INNER JOIN student ON student.id = enrollment.student_id
            INNER JOIN institution ON institution.id = enrollment.institution_id
            INNER JOIN schoolyears ON schoolyears.id = enrollment.school_year_id
            INNER JOIN group_assignment ON group_assignment.enrollment_id = enrollment.id
            INNER JOIN `group` ON `group`.id = group_assignment.group_id
            INNER JOIN headquarter ON headquarter.id = group.headquarter_id AND headquarter.institution_id =  institution.id 

            INNER JOIN group_pensum ON group_pensum.group_id = `group`.id
            AND group_pensum.asignatures_id = evaluation_periods.asignatures_id
            INNER JOIN areas ON areas.id = group_pensum.areas_id
            INNER JOIN asignatures ON asignatures.id = group_pensum.asignatures_id
            WHERE `group`.id = {$group_id} AND
            institution.id = {$institution_id} AND
            schoolyears.id = {$school_year_id} AND
            evaluation_periods.periods_id = {$period_id}
            GROUP BY enrollment.id, asignatures.id
            ) result
            GROUP BY result.enrollment_id, result.areas_id
            ORDER BY result.last_name, result.name"));
    }

    public static function getAverageSubGroupPensum($subgroup_id, $institution_id, $school_year_id, $period_id)
    {
        return DB::select(DB::raw("SELECT result.enrollment_id, result.last_name, result.name, result.name_areas, SUM(result.percent) percent,
            ROUND(IF((SUM(result.percent) = 100), 
                SUM((result.percent/100) * result.value), 
                SUM(result.value)/SUM((result.value>0))), 2) average, 
            SUM(result.tav) tav,
            result.areas_id, result.sgName
            from
            (SELECT 
            enrollment.id as 'enrollment_id',
            student.last_name as 'last_name', student.name as 'name', areas.`name` as 'name_areas', sub_group_pensum.percent as 'percent', sub_group.name as 'sgName',
            notes_final.`value` as 'value', (notes_final.`value`>0) tav, areas.id as 'areas_id'
            FROM notes_final
            INNER JOIN evaluation_periods ON evaluation_periods.id = notes_final.evaluation_periods_id
            INNER JOIN enrollment ON enrollment.id = evaluation_periods.enrollment_id
            INNER JOIN student ON student.id = enrollment.student_id
            INNER JOIN institution ON institution.id = enrollment.institution_id
            INNER JOIN schoolyears ON schoolyears.id = enrollment.school_year_id
            INNER JOIN sub_group_assignments ON sub_group_assignments.enrollment_id = enrollment.id
            INNER JOIN sub_group ON sub_group.id = sub_group_assignments.subgroup_id
            INNER JOIN headquarter ON headquarter.id = sub_group.headquarter_id AND headquarter.institution_id =  institution.id
            INNER JOIN sub_group_pensum ON sub_group_pensum.sub_group_id = sub_group.id
            AND sub_group_pensum.asignatures_id = evaluation_periods.asignatures_id
            INNER JOIN areas ON areas.id = sub_group_pensum.areas_id
            INNER JOIN asignatures ON asignatures.id = sub_group_pensum.asignatures_id
            WHERE sub_group.id = {$subgroup_id} AND
            institution.id = {$institution_id} AND
            schoolyears.id = {$school_year_id} AND
            evaluation_periods.periods_id = {$period_id}
            GROUP BY enrollment.id, asignatures.id
            ) result
            GROUP BY result.enrollment_id, result.areas_id
            ORDER BY result.last_name, result.name"));
    }






    public static function getNotesFilterByAreas($params)
    {

        $data = DB::select(DB::raw(
            "SELECT result.enrollment_id, result.last_name, result.name, result.name_areas, 
                SUM(result.percent) percent, ROUND(IF((SUM(result.percent) = 100),
                SUM((result.percent/100) * result.value),
                SUM(result.value)/SUM((result.value>0))), 2) 'value',
                SUM(result.tav) tav, result.areas_id as 'asignatures_id', result.overcoming,
                result.evaluation_periods_id, result.periods_id, result.notes_final_id
                from
                (
                SELECT
                enrollment.id as 'enrollment_id', notes_final.value as 'value', notes_final.overcoming,
                notes_final.id as 'notes_final_id', areas.id as 'areas_id', evaluation_periods.periods_id, 
                student.last_name as 'last_name', student.name as 'name', areas.`name` as 'name_areas',                
                evaluation_periods.id as 'evaluation_periods_id',
                group_pensum.percent as 'percent', 
                (notes_final.value>0) as 'tav'
                FROM notes_final
                INNER JOIN evaluation_periods ON evaluation_periods.id = notes_final.evaluation_periods_id
                INNER JOIN enrollment ON enrollment.id = evaluation_periods.enrollment_id
                INNER JOIN student ON student.id = enrollment.student_id
                INNER JOIN institution ON institution.id = enrollment.institution_id
                INNER JOIN schoolyears ON schoolyears.id = enrollment.school_year_id
                INNER JOIN group_assignment ON group_assignment.enrollment_id = enrollment.id
                INNER JOIN `group` ON `group`.id = group_assignment.group_id
                INNER JOIN headquarter ON headquarter.id = group.headquarter_id AND headquarter.institution_id =  institution.id 
                INNER JOIN group_pensum ON group_pensum.group_id = `group`.id
                AND group_pensum.asignatures_id = evaluation_periods.asignatures_id
                INNER JOIN areas ON areas.id = group_pensum.areas_id
                INNER JOIN asignatures ON asignatures.id = group_pensum.asignatures_id
                WHERE `group`.id = '$params->group_id' AND
                institution.id = '$params->institution_id' AND
                schoolyears.id = 1                 
                GROUP BY enrollment.id, asignatures.id, evaluation_periods.periods_id
                ) as
                result
                GROUP BY result.enrollment_id, result.areas_id, result.periods_id
                ORDER BY result.last_name, result.name
                "
        ));

        return $data;
    }

    public static function getNotesFilterByAsignatures($params)
    {

        $data = DB::table('notes_final')
            ->select('enrollment.id as enrollment_id', 'notes_final.value', 'notes_final.overcoming',
                'notes_final.id as notes_final_id', 'evaluation_periods.asignatures_id',
                'asignatures.name as asignature_name',
                'evaluation_periods.periods_id',
                'evaluation_periods.id as evaluation_periods_id')
            ->join('evaluation_periods', 'evaluation_periods.id', '=', 'notes_final.evaluation_periods_id')
            ->join('enrollment', 'enrollment.id', '=', 'evaluation_periods.enrollment_id')
            ->join('grade', 'enrollment.grade_id', '=', 'grade.id')
            ->join('group_assignment', 'enrollment.id', '=', 'group_assignment.enrollment_id')
            ->join('group', 'group_assignment.group_id', '=', 'group.id')
            ->join('group_pensum', 'group_pensum.group_id', '=', 'group.id')
            ->join('asignatures', 'asignatures.id', '=', 'group_pensum.asignatures_id')
            ->join('institution', 'enrollment.institution_id', '=', 'institution.id')
            ->join('headquarter', 'institution.id', '=', 'headquarter.institution_id')
            ->join('schoolyears', 'enrollment.school_year_id', 'schoolyears.id')
            ->whereColumn(
                [
                    ['headquarter.id', '=', 'group.headquarter_id'],
                    ['group.grade_id', '=', 'grade.id'],
                    ['group_pensum.asignatures_id', '=', 'evaluation_periods.asignatures_id']
                ]
            )
            ->where('group.id', '=', $params->group_id)
            ->where('institution.id', '=', $params->institution_id)
            ->where('schoolyears.id', '=', '1')
            ->get();

        return $data;
    }



}
