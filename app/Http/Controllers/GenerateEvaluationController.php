<?php

namespace App\Http\Controllers;

use App\EvaluationParameter;
use App\EvaluationPeriod;
use App\Group;
use App\GroupPensum;
use App\Note;
use App\NotesParameters;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GenerateEvaluationController extends Controller
{
    public function index()
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        $institution = Auth::guard('web_institution')->user();

        $parameters = $institution->evaluationParameters()
            ->with('notesParameter')
            ->with('schoolYear')
            ->get();
        $groups = Group::getAllByInstitution($institution_id);
        $period = 1;
        $count = 0;
        $c = 0;

        return "";
    }
}
