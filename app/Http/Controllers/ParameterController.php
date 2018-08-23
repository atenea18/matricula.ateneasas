<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParameterController extends Controller
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


    public function getEvaluationParameter(Request $request)
    {
        $parameters = $this->institution->evaluationParameters()
            ->with('notesParameter')
            ->with('schoolYear')
            ->get();


        $notes = DB::table('evaluation_parameters')
            ->select('notes_parameters.name as notes_parameters_name', 'notes_parameters.id as notes_parameters_id',
                'evaluation_parameters.parameter as evaluation_parameters_name',
                'evaluation_parameters.id as evaluation_parameters_id',
                'notes_parameters_criterias.id as notes_parameters_criterias_id',
                'criterias.id as criterias_id', 'criterias.parameter as criterias_name',
                'criterias.abbreviation as criterias_abbreviation')
            ->join('notes_parameters', 'notes_parameters.evaluation_parameter_id', '=', 'evaluation_parameters.id')
            ->join('notes_parameters_criterias', 'notes_parameters_criterias.notes_parameters_id', '=', 'notes_parameters.id')
            ->join('criterias', 'criterias.id', '=', 'notes_parameters_criterias.criterias_id')
            ->where('evaluation_parameters.institution_id', '=', $this->institution->id)
            ->get();


        foreach ($parameters as $key => $para) {
            if ($para->evaluation_type_id == 2) {
                foreach ($para->notesParameter as $note) {
                    foreach ($notes as $noteAux) {
                        if ($note->id == $noteAux->notes_parameters_id) {
                            $note->criterias = $noteAux;
                        }
                    }
                }
            }
        }
        return $parameters;
    }
}
