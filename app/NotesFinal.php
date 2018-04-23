<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
