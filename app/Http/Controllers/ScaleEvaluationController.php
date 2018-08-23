<?php

namespace App\Http\Controllers;

use App\ScaleEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScaleEvaluationController extends Controller
{
    private $teacher = null;
    private $institution = null;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (Auth::guard('teachers')->check()) {
                $this->teacher = Auth::guard('teachers')->user()->teachers()->first();
                $this->institution = $this->teacher->institution;
            } elseif (Auth::guard('web_institution')->check()) {
                $this->institution = Auth::guard('web_institution')->user();
            }
            return $next($request);
        });
    }

    public function getScaleEvaluation(Request $request)
    {

        $scaleEvaluation = ScaleEvaluation::select('scale_evaluations.id', 'scale_evaluations.name', 'scale_evaluations.abbreviation',
            'scale_evaluations.rank_start', 'scale_evaluations.rank_end', 'scale_evaluations.name_recommendation',
            'words_expressions.name as words_expressions_name', 'words_expressions.id as words_expressions_id')
            ->where('scale_evaluations.institution_id', '=', $this->institution->id)
            ->join('words_expressions', 'words_expressions.id', '=', 'scale_evaluations.words_expressions_id')
            ->get();

        return $scaleEvaluation;
    }
}
