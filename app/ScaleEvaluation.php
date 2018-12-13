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
            ['words_expressions_id', '=', 4],
            ['school_year_id', '=', 1],
            ['institution_id', '=', $institution->id]
        ])->first();
    }

    public static function getBasicScale(Institution $institution)
    {
        return ScaleEvaluation::where([
            ['words_expressions_id', '=', 3],
            ['school_year_id', '=', 1],
            ['institution_id', '=', $institution->id]
        ])->first();
    }

    public static function getHighScale(Institution $institution)
    {
        return ScaleEvaluation::where([
            ['words_expressions_id', '=', 1],
            ['school_year_id', '=', 1],
            ['institution_id', '=', $institution->id]
        ])
        ->first();
    }

    public static function getScaleByInstitution($institution_id){
        return self::select('scale_evaluations.id', 'scale_evaluations.name', 'scale_evaluations.abbreviation',
            'scale_evaluations.rank_start', 'scale_evaluations.rank_end', 'scale_evaluations.name_recommendation',
            'words_expressions.name as words_expressions_name', 'words_expressions.id as words_expressions_id')
            ->where('scale_evaluations.institution_id', '=', $institution_id)
            ->join('words_expressions', 'words_expressions.id', '=', 'scale_evaluations.words_expressions_id')
            ->get();

    }
}
