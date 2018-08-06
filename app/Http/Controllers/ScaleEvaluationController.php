<?php

namespace App\Http\Controllers;

use App\ScaleEvaluation;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ScaleEvaluationController extends Controller
{
    public function getScaleEvaluation(Request $request)
    {

        $scaleEvaluation = ScaleEvaluation::select('scale_evaluations.id', 'scale_evaluations.name', 'scale_evaluations.abbreviation',
            'scale_evaluations.rank_start', 'scale_evaluations.rank_end', 'scale_evaluations.name_recommendation',
            'words_expressions.name as words_expressions_name', 'words_expressions.id as words_expressions_id')
            ->where('scale_evaluations.institution_id', '=', $request->institution_id)
            ->join('words_expressions', 'words_expressions.id', '=', 'scale_evaluations.words_expressions_id')
            ->get();

        return $scaleEvaluation;
    }
}
