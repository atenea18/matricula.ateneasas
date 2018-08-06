<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WordExpression extends Model
{
    protected $table = "words_expressions";

    protected $fillable = [
    	'name'
    ];

    public function scaleEvaluations()
    {
    	return $this->hasMany(ScaleEvaluation::class, 'words_expressions_id');
    }
}
