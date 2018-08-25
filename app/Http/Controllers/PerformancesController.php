<?php

namespace App\Http\Controllers;

use App\MessagesExpressions;
use App\MessagesScale;
use App\Performances;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class PerformancesController extends Controller
{
    private $teacher = null;
    private $institution = null;
    private $params = null;

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

    public function searchPerformances(Request $request)
    {
        $pensum = DB::table('performances')
            ->select('performances.id', 'messages_expressions.name')
            ->join('messages_expressions', 'messages_expressions.id', '=', 'performances.messages_expressions_id')
            ->where('performances.pensum_id', '=', $request->pensum_id)
            ->where('performances.periods_id', '=', $request->periods_id)
            ->where('performances.evaluation_parameters_id', '=', $request->evaluation_parameters_id)
            ->get();

        return $pensum;
    }

    public function store(Request $request)
    {
        $params = $request->data;
        $messageExpressions = new MessagesExpressions();
        foreach ($params['vector_texts'] as $input_text) {
            if ($input_text['is_higher']) {
                try {
                    $messageExpressions->name = $input_text['name'];
                    $messageExpressions->institution_id = $this->institution->id;
                    $messageExpressions->save();
                } catch (\Exception $e) {
                    $messageExpressions->id = 0;
                }
                $performances = new Performances();
                if ($messageExpressions->id != 0) {
                    try {
                        $performances->pensum_id = $params['pensum_id'];
                        $performances->evaluation_parameters_id = $params['parameter_id'];
                        $performances->periods_id = $params['period_id'];
                        $performances->messages_expressions_id = $messageExpressions->id;
                        $performances->save();
                    } catch (\Exception $e) {
                        $performances->id = 0;
                    }
                }
            } else {
                $messageScale = new MessagesScale();
                if ($messageExpressions->id != 0) {
                    try {
                        $messageScale->name = $input_text['name'];
                        $messageScale->recommendation = $input_text['recommendation'];
                        $messageScale->scale_evaluations_id = $input_text['scale_id'];
                        $messageScale->messages_expressions_id = $messageExpressions->id;
                        $messageScale->save();
                    } catch (\Exception $e) {
                        $messageScale->id = 0;
                    }
                }
            }
        }
        return $performances;
    }

}
