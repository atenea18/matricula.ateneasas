<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $fillable = [
    	'parameter', 'abbreviation', 'evaluation_parameter_id'
    ];

    public function evaluationParameter()
    {
    	return $this->belongsTo(EvaluationParameter::class, 'evaluation_parameter_id');
    }
}
