<?php

namespace App\Http\Controllers\Teacher;

use App\Asignature;
use App\Enrollment;
use App\Group;
use App\Institution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teachers')->user()->teachers()->first();

        // dd($teacher);
        $pemsun = $teacher->pensums()
            ->where('schoolyear_id', '=', 1)
            ->with('asignature')
            ->with('area')
            ->with('group')
            ->with('subjectType')
            ->with('schoolYear')
            ->get();


        // dd($pemsun);
        return View('teacher.partials.evaluation.index')
            ->with('teacher', $teacher)
            ->with('pemsun', $pemsun);
    }

    public function evaluationPeriods($group_id, $asignatures_id, $period_id = 1)
    {
        $teacher = Auth::guard('teachers')->user()->teachers()->first();

        $enrollments = Group::enrollmentsByGroup($teacher->institution_id, $group_id);

        $notes = DB::table('notes')
            ->select('enrollment.id as enrollment_id', 'notes.value', 'notes.overcomming', 'notes.id as notes_id',
                'notes.notes_parameters_id', 'notes_parameters.evaluation_parameter_id')
            ->join('notes_parameters', 'notes_parameters.id', '=', 'notes.notes_parameters_id')
            ->join('evaluation_periods', 'evaluation_periods.id', '=', 'notes.evaluation_periods_id')
            ->join('enrollment', 'enrollment.id', '=', 'evaluation_periods.enrollment_id')
            ->join('evaluation_parameters','evaluation_parameters.id','=','notes_parameters.evaluation_parameter_id')
            ->where('evaluation_periods.asignatures_id', '=', $asignatures_id)
            ->where('evaluation_periods.periods_id', '=', $period_id)
            ->get();

        $id_after = 0;

        #representa una lista de estudiantes con sus notas existente
        $collection = [];

        foreach ($enrollments as $key => $enrollment) {
            $id_after = $enrollment->id;
            $collection_notes = [];
            foreach ($notes as $note) {

                if ($id_after == $note->enrollment_id) {
                    array_push($collection_notes, $note);
                }
            }

            $enrollment->notes = $collection_notes;
            array_push($collection, $enrollment);


        }

        $group = Group::where('id', '=', $group_id)->get();



        return View('teacher.partials.evaluation.evaluationPeriods')
            ->with('group', $group[0])
            ->with('enrollments', $enrollments)
            ->with('collectionNotes', $collection)
            ->with('asignature_id', $asignatures_id);

    }

    public function getAsignatureById($asignatures_id){
        $asignatures = Asignature::where('id', '=', $asignatures_id)->get();
        return $asignatures[0];
    }

    public function evaluationParameter(Request $request)
    {

        $teacher = Auth::guard('teachers')->user()->teachers()->first();
        $institution = Institution::where('id', '=', $teacher->institution_id)->get();

        $parameters = $institution[0]->evaluationParameters()
            ->with('notesParameter')
            ->with('schoolYear')
            ->get();

        return $parameters;
        if ($request->ajax()) {

        }

    }
}
