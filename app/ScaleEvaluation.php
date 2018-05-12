<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScaleEvaluation extends Model
{
    protected $fillable = [
    	'name', 'abbreviation', 'rank_start', 'rank_end', 'institution_id', 'school_year_id', 'words_expressions_id'
    ];

    public function institution()
    {
    	return $this->belongsTo(Institution::class, 'institution_id');	
    }

    public function schoolyear()
    {
    	return $this->belongsTo(Schoolyear::class, 'school_year_id');
    }

    public function wordExpresion()
    {
    	return $this->belongsTo(WordExpression::class, 'words_expressions_id');
    }

    public static function getMinScale(Institution $institution)
    {
        return ScaleEvaluation::where([
            ['abbreviation', '=', 'bj'],
            ['school_year_id', '=', 1],
            ['institution_id', '=', $institution->id]
        ])
        ->first();
    }

    public static function getHighScale(Institution $institution)
    {
        return ScaleEvaluation::where([
            ['abbreviation', '=', 's'],
            ['school_year_id', '=', 1],
            ['institution_id', '=', $institution->id]
        ])
        ->first();
    }
}
