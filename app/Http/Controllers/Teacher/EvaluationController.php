<?php

namespace App\Http\Controllers\Teacher;

use App\Asignature;
use App\Enrollment;
use App\EvaluationPeriod;
use App\Group;
use App\Institution;
use App\Note;
use App\NotesFinal;
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


        //dd($pemsun);
        return View('teacher.partials.evaluation.index')
            ->with('teacher', $teacher)
            ->with('pemsun', $pemsun);
    }

    public function evaluationPeriods($group_id, $asignatures_id)
    {
        $group = Group::where('id', '=', $group_id)->get();

        return View('teacher.partials.evaluation.evaluationPeriods')
            ->with('group', $group[0])
            ->with('asignature_id', $asignatures_id);
    }


    public function storeEvaluationPeriods(request $request)
    {
        $data = $request->data;
        $evaluation = null;
        $evaluation = EvaluationPeriod::where('enrollment_id', '=', $data['enrollment_id'])
            ->where('periods_id', '=', $data['periods_id'])
            ->where('asignatures_id', $data['asignatures_id'])
            ->first();
        if (!$evaluation) {
            try {
                $evaluation = new EvaluationPeriod();
                $evaluation->code_evaluation_periods = $data['enrollment_id'] . $data['periods_id'] . $data['asignatures_id'];
                $evaluation->enrollment_id = $data['enrollment_id'];
                $evaluation->periods_id = $data['periods_id'];
                $evaluation->asignatures_id = $data['asignatures_id'];
                $evaluation->save();
            } catch (\Exception $e) {

            }
        }

        return $evaluation;

    }

    public function storeFinalNotes(request $request)
    {
        $data = $request->data;
        $notesFinal = null;

        $notesFinal = NotesFinal::where('evaluation_periods_id', '=', $data['evaluation_periods_id'])
            ->first();

        if (!$notesFinal) {
            try {
                $notesFinal = new NotesFinal();
                $notesFinal->code_notes_final = $data['evaluation_periods_id'];
                $notesFinal->value = $data['value'];
                $notesFinal->overcoming = $data['overcoming'];
                $notesFinal->evaluation_periods_id = $data['evaluation_periods_id'];
                $notesFinal->save();

            } catch (\Exception $e) {

            }
        } else {
            NotesFinal::where('evaluation_periods_id', '=', $data['evaluation_periods_id'])
                ->update([
                    'code_notes_final' => $data['evaluation_periods_id'],
                    'value' => $data['value'],
                    'overcoming' => $data['overcoming'],
                ]);
        }

        $notesFinal = NotesFinal::where('evaluation_periods_id', '=', $data['evaluation_periods_id'])
            ->first();
        return $notesFinal;

    }

    public function storeNotes(request $request)
    {
        $data = $request->data;
        $notes = null;

        $notes = Note::where('evaluation_periods_id', '=', $data['evaluation_periods_id'])
            ->where('notes_parameters_id', '=', $data['notes_parameters_id'])
            ->first();


        if (!$notes) {
            try {
                $notes = new Note();
                $notes->code_notes = $data['evaluation_periods_id'] .'' . $data['notes_parameters_id'];
                $notes->value = $data['value'];
                $notes->overcoming = $data['overcoming'];
                $notes->notes_parameters_id = $data['notes_parameters_id'];
                $notes->evaluation_periods_id = $data['evaluation_periods_id'];
                $notes->save();

            } catch (\Exception $e) {

            }
        } else {
            Note::where('evaluation_periods_id', '=', $data['evaluation_periods_id'])
                ->where('notes_parameters_id', '=', $data['notes_parameters_id'])
                ->update([
                    'code_notes' => $data['evaluation_periods_id'] . "" . $data['notes_parameters_id'],
                    'value' => $data['value'],
                    'overcoming' => $data['overcoming'],
                ]);
        }

        $notes = Note::where('evaluation_periods_id', '=', $data['evaluation_periods_id'])
            ->where('notes_parameters_id', '=', $data['notes_parameters_id'])
            ->first();


        return $notes;

    }


    public function getAsignatureById($asignatures_id)
    {
        $asignatures = Asignature::where('id', '=', $asignatures_id)->get();
        return $asignatures[0];
    }

    public function getPeriodsByWorkingDay($working_day_id)
    {
        $teacher = Auth::guard('teachers')->user()->teachers()->first();
        $institution_id = $teacher->institution_id;

        $periodsWorkingDay = DB::table('working_day_periods')
            ->select(
                'periods.name as periods_name', 'periods.id as periods_id',
                'working_day_periods.start_date', 'working_day_periods.end_date', 'working_day_periods.percent', 'working_day_periods.id as working_day_periods_id',
                'periods_state.name as periods_state_name', 'periods_state.id as periods_state_id'
            )
            ->join('periods_state', 'periods_state.id', '=', 'working_day_periods.periods_state_id')
            ->join('periods', 'periods.id', '=', 'working_day_periods.periods_id')
            ->where('working_day_periods.working_day_id', '=', $working_day_id)
            ->where('working_day_periods.institution_id', '=', $institution_id)
            ->get();

        return $periodsWorkingDay;
    }

    public function getCollectionsNotes($group_id, $asignatures_id, $period_id = 1)
    {
        $teacher = Auth::guard('teachers')->user()->teachers()->first();
        $enrollments = Group::enrollmentsByGroup($teacher->institution_id, $group_id);

        $notes = DB::table('notes')
            ->select('enrollment.id as enrollment_id', 'notes.value', 'notes.overcoming', 'notes.id as notes_id',
                'notes.notes_parameters_id', 'notes_parameters.evaluation_parameter_id')
            ->join('notes_parameters', 'notes_parameters.id', '=', 'notes.notes_parameters_id')
            ->join('evaluation_periods', 'evaluation_periods.id', '=', 'notes.evaluation_periods_id')
            ->join('enrollment', 'enrollment.id', '=', 'evaluation_periods.enrollment_id')
            ->join('evaluation_parameters', 'evaluation_parameters.id', '=', 'notes_parameters.evaluation_parameter_id')
            ->where('evaluation_periods.asignatures_id', '=', $asignatures_id)
            ->where('evaluation_periods.periods_id', '=', $period_id)
            ->get();

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

        return $collection;
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
