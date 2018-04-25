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
    		
    		$this->save();

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
            ->join('institution', 'enrollment.institution_id', '=', 'institution.id')
            ->join('headquarter', 'institution.id', '=', 'headquarter.institution_id')
            ->join('schoolyears', 'enrollment.school_year_id', 'schoolyears.id')
            ->whereColumn(
                [
                    ['headquarter.id', '=', 'group.headquarter_id'],
                    ['group.grade_id', '=', 'grade.id']
                ]
            )
            ->where('group.id', '=', $data->group_id)
            ->where('institution.id', '=', $data->institution_id)
            ->where('schoolyears.id', '=', '1')
            ->where('evaluation_periods.periods_id', '=', $data->period_id)
            ->get();


        // return $data->period_id;
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
        // return $data;
    }
}
