<?php

namespace App\Http\Controllers\Teacher;

use App\Asignature;
use App\Enrollment;
use App\EvaluationPeriod;
use App\Grade;
use App\Group;
use App\Institution;
use App\MessagesExpressions;
use App\MessagesScale;
use App\NoAttendance;
use App\Note;
use App\NotesFinal;
use App\NotesParametersPerformances;
use App\Performances;
use App\Subgroup;
use App\NotesParametersPerformancesSub;
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

        $sub_pemsun = $teacher->sub_pensums()
            ->where('schoolyear_id', '=', 1)
            ->with('asignature')
            ->with('area')
            ->with('subgroup')
            ->with('subjectType')
            ->with('schoolYear')
            ->get();


        return View('teacher.partials.evaluation.index')
            ->with('teacher', $teacher)
            ->with('pemsun', $pemsun)
            ->with('sub_pensum', $sub_pemsun);

    }

    public function evaluationPeriods($group_id, $type, $asignatures_id)
    {
        $itemGroup = null;


        if ($type == "group") {
            $group = Group::where('id', '=', $group_id)->get();
            $itemGroup = $group[0];
        } else {
            $sub_group = Subgroup::where('id', '=', $group_id)->get();
            $itemGroup = $sub_group[0];
        }


        return View('teacher.partials.evaluation.evaluationPeriods')
            ->with('itemGroup', $itemGroup)
            ->with('asignature_id', $asignatures_id)
            ->with('filter', $type);
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

        $evaluation = EvaluationPeriod::where('enrollment_id', '=', $data['enrollment_id'])
            ->where('periods_id', '=', $data['periods_id'])
            ->where('asignatures_id', $data['asignatures_id'])
            ->first();

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
                $notes->code_notes = $data['evaluation_periods_id'] . '' . $data['notes_parameters_id'];
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

    public function storeNoAttendance(request $request)
    {
        $data = $request->data;
        $noAttendance = null;

        $noAttendance = NoAttendance::where('evaluation_periods_id', '=', $data['evaluation_periods_id'])
            ->first();

        if (!$noAttendance) {
            try {
                $noAttendance = new NoAttendance();
                $noAttendance->evaluation_periods_id = $data['evaluation_periods_id'];
                $noAttendance->quantity = $data['quantity'];
                $noAttendance->save();

            } catch (\Exception $e) {

            }
        } else {
            NoAttendance::where('evaluation_periods_id', '=', $data['evaluation_periods_id'])
                ->update([
                    'quantity' => $data['quantity'],
                ]);
        }

        $noAttendance = NoAttendance::where('evaluation_periods_id', '=', $data['evaluation_periods_id'])
            ->first();

        return $noAttendance;

    }


    public function getAsignatureById(Request $request)
    {
        if ($request->isGroup == "true") {
            $asignatures = Asignature::select('asignatures.id', 'asignatures.name', 'pensum.areas_id', 'pensum.grade_id', 'pensum.id as pensum_id')
                ->join('pensum', 'pensum.asignatures_id', '=', 'asignatures.id')
                ->where('pensum.grade_id', '=', $request->grade_id)
                ->where('asignatures.id', '=', $request->asignatureid)
                ->get();
            return $asignatures[0];
        } else {
            $asignatures = Asignature::select('asignatures.id', 'asignatures.name',
                'sub_group_pensum.areas_id as areas_id',
                'sub_group_pensum.id as pensum_id')
                ->join('sub_group_pensum', 'sub_group_pensum.asignatures_id', '=', 'asignatures.id')
                ->where('asignatures.id', '=', $request->asignatureid)
                ->get();
            return $asignatures[0];
        }

    }

    public function getSubAsignatureById($asignatures_id, $grade_id)
    {
        $asignatures = Asignature::select('asignatures.id', 'asignatures.name', 'sub_group_pensum.areas_id', 'sub_group_pensum.grade_id', 'sub_group_pensum.id as sub_group_pensum_id')
            ->join('sub_group_pensum', 'sub_group_pensum.asignatures_id', '=', 'asignatures.id')
            ->where('sub_group_pensum.grade_id', '=', $grade_id)
            ->where('asignatures.id', '=', $asignatures_id)
            ->get();
        return $asignatures[0];
    }

    public function getGradeById($grade_id)
    {
        $grade = Grade::where('id', '=', $grade_id)->get();
        return $grade[0];
    }


    public function getPeriodsByWorkingDay(Request $request)
    {

        $teacher = Auth::guard('teachers')->user()->teachers()->first();
        $institution_id = $teacher->institution_id;

        if ($request->isGroup == "true") {
            $periodsWorkingDay = DB::table('working_day_periods')
                ->select(
                    'periods.name as periods_name', 'periods.id as periods_id',
                    'working_day_periods.start_date', 'working_day_periods.end_date', 'working_day_periods.percent', 'working_day_periods.id as working_day_periods_id',
                    'periods_state.name as periods_state_name', 'periods_state.id as periods_state_id'
                )
                ->join('periods_state', 'periods_state.id', '=', 'working_day_periods.periods_state_id')
                ->join('periods', 'periods.id', '=', 'working_day_periods.periods_id')
                ->where('working_day_periods.working_day_id', '=', $request->workingdayid)
                ->where('working_day_periods.institution_id', '=', $institution_id)
                ->get();

            return $periodsWorkingDay;
        } else {
            $periodsWorkingDay = DB::table('section_periods')
                ->select(
                    'periods.name as periods_name', 'periods.id as periods_id',
                    'section_periods.start_date', 'section_periods.end_date', 'section_periods.percent', 'section_periods.id as working_day_periods_id',
                    'periods_state.name as periods_state_name', 'periods_state.id as periods_state_id'
                )
                ->join('periods_state', 'periods_state.id', '=', 'section_periods.periods_state_id')
                ->join('periods', 'periods.id', '=', 'section_periods.periods_id')
                ->where('section_periods.section_id', '=', $request->workingdayid)
                ->get();

            return $periodsWorkingDay;
        }

    }

    public function getCollectionsNotes(Request $request)
    {

        $teacher = Auth::guard('teachers')->user()->teachers()->first();
        //$enrollments = Group::enrollmentsByGroup($teacher->institution_id, $request->groupid);

        if ($request->isGroup == "true") {
            $enrollments = Group::enrollmentsByGroup($teacher->institution_id, $request->groupid);

            $notes = DB::table('notes')
                ->select('enrollment.id as enrollment_id', 'notes.value', 'notes.overcoming', 'notes.id as notes_id',
                    'notes.notes_parameters_id', 'notes_parameters.evaluation_parameter_id')
                ->join('notes_parameters', 'notes_parameters.id', '=', 'notes.notes_parameters_id')
                ->join('evaluation_periods', 'evaluation_periods.id', '=', 'notes.evaluation_periods_id')
                ->join('enrollment', 'enrollment.id', '=', 'evaluation_periods.enrollment_id')
                ->join('evaluation_parameters', 'evaluation_parameters.id', '=', 'notes_parameters.evaluation_parameter_id')
                ->join('grade', 'enrollment.grade_id', '=', 'grade.id')
                ->join('group_assignment', 'enrollment.id', '=', 'group_assignment.enrollment_id')
                ->join('group', 'group_assignment.group_id', '=', 'group.id')
                ->join('institution', 'enrollment.institution_id', '=', 'institution.id')
                ->join('headquarter', 'institution.id', '=', 'headquarter.institution_id')
                ->join('schoolyears', 'enrollment.school_year_id', 'schoolyears.id')
                ->whereColumn(
                    [
                        ['headquarter.id', '=', 'group.headquarter_id'],
                        ['group.grade_id', '=', 'grade.id']
                    ]
                )
                ->where('group.id', '=', $request->groupid)
                ->where('institution.id', '=', $teacher->institution_id)
                ->where('schoolyears.id', '=', '1')
                ->where('evaluation_periods.asignatures_id', '=', $request->asignatureid)
                ->where('evaluation_periods.periods_id', '=', $request->periodid)
                ->get();

            $no_attendance = DB::table('enrollment')
                ->select('enrollment.id as enrollment_id', 'evaluation_periods.id as evaluation_periods_id',
                    'no_attendance.id as no_attendance_id', 'no_attendance.quantity'
                )
                ->join('grade', 'enrollment.grade_id', '=', 'grade.id')
                ->join('group_assignment', 'enrollment.id', '=', 'group_assignment.enrollment_id')
                ->join('group', 'group_assignment.group_id', '=', 'group.id')
                ->join('institution', 'enrollment.institution_id', '=', 'institution.id')
                ->join('headquarter', 'institution.id', '=', 'headquarter.institution_id')
                ->join('schoolyears', 'enrollment.school_year_id', 'schoolyears.id')
                ->leftJoin('evaluation_periods', 'evaluation_periods.enrollment_id', '=', 'enrollment.id')
                ->leftJoin('no_attendance', 'no_attendance.evaluation_periods_id', '=', 'evaluation_periods.id')
                ->whereColumn(
                    [
                        ['headquarter.id', '=', 'group.headquarter_id'],
                        ['group.grade_id', '=', 'grade.id']
                    ]
                )
                ->where('group.id', '=', $request->groupid)
                ->where('institution.id', '=', $teacher->institution_id)
                ->where('schoolyears.id', '=', '1')
                ->where('evaluation_periods.asignatures_id', '=', $request->asignatureid)
                ->where('evaluation_periods.periods_id', '=', $request->periodid)
                ->get();
        } else {

            $enrollments = Subgroup::enrollmentsBySubGroup($teacher->institution_id, $request->groupid);


            $notes = DB::table('notes')
                ->select('enrollment.id as enrollment_id', 'notes.value', 'notes.overcoming', 'notes.id as notes_id',
                    'notes.notes_parameters_id', 'notes_parameters.evaluation_parameter_id')
                ->join('notes_parameters', 'notes_parameters.id', '=', 'notes.notes_parameters_id')
                ->join('evaluation_periods', 'evaluation_periods.id', '=', 'notes.evaluation_periods_id')
                ->join('enrollment', 'enrollment.id', '=', 'evaluation_periods.enrollment_id')
                ->join('evaluation_parameters', 'evaluation_parameters.id', '=', 'notes_parameters.evaluation_parameter_id')
                ->join('grade', 'enrollment.grade_id', '=', 'grade.id')
                ->join('sub_group_assignments', 'enrollment.id', '=', 'sub_group_assignments.enrollment_id')
                ->join('sub_group', 'sub_group_assignments.subgroup_id', '=', 'sub_group.id')
                ->join('institution', 'enrollment.institution_id', '=', 'institution.id')
                ->join('headquarter', 'institution.id', '=', 'headquarter.institution_id')
                ->join('schoolyears', 'enrollment.school_year_id', 'schoolyears.id')
                ->whereColumn(
                    [
                        ['headquarter.id', '=', 'sub_group.headquarter_id'],
                        ['sub_group.grade_id', '=', 'grade.id']
                    ]
                )
                ->where('sub_group.id', '=', $request->groupid)
                ->where('institution.id', '=', $teacher->institution_id)
                ->where('schoolyears.id', '=', '1')
                ->where('evaluation_periods.asignatures_id', '=', $request->asignatureid)
                ->where('evaluation_periods.periods_id', '=', $request->periodid)
                ->get();

            $no_attendance = DB::table('enrollment')
                ->select('enrollment.id as enrollment_id', 'evaluation_periods.id as evaluation_periods_id',
                    'no_attendance.id as no_attendance_id', 'no_attendance.quantity'
                )
                ->join('grade', 'enrollment.grade_id', '=', 'grade.id')
                ->join('sub_group_assignments', 'enrollment.id', '=', 'sub_group_assignments.enrollment_id')
                ->join('sub_group', 'sub_group_assignments.subgroup_id', '=', 'sub_group.id')
                ->join('institution', 'enrollment.institution_id', '=', 'institution.id')
                ->join('headquarter', 'institution.id', '=', 'headquarter.institution_id')
                ->join('schoolyears', 'enrollment.school_year_id', 'schoolyears.id')
                ->leftJoin('evaluation_periods', 'evaluation_periods.enrollment_id', '=', 'enrollment.id')
                ->leftJoin('no_attendance', 'no_attendance.evaluation_periods_id', '=', 'evaluation_periods.id')
                ->whereColumn(
                    [
                        ['headquarter.id', '=', 'sub_group.headquarter_id'],
                        ['sub_group.grade_id', '=', 'grade.id']
                    ]
                )
                ->where('sub_group.id', '=', $request->groupid)
                ->where('institution.id', '=', $teacher->institution_id)
                ->where('schoolyears.id', '=', '1')
                ->where('evaluation_periods.asignatures_id', '=', $request->asignatureid)
                ->where('evaluation_periods.periods_id', '=', $request->periodid)
                ->get();


        }


        #representa una lista de estudiantes con sus notas existente
        $collection = [];

        foreach ($enrollments as $key => $enrollment) {

            $collection_notes = [];
            foreach ($notes as $keyNotes => $note) {
                if ($enrollment->id == $note->enrollment_id) {
                    array_push($collection_notes, $note);
                    unset($notes[$keyNotes]);
                }
            }
            if (count($no_attendance) >= 1) {
                foreach ($no_attendance as $keyNoAtt => $item) {
                    if ($enrollment->id == $item->enrollment_id) {
                        $enrollment->no_attendance = $item->quantity;
                        $enrollment->evaluation_periods_id = $item->evaluation_periods_id;
                        unset($no_attendance[$keyNoAtt]);
                    }
                }
            }
            if (count($no_attendance) < 1) {
                $enrollment->no_attendance = '';
                $enrollment->evaluation_periods_id = 0;
            }

            $enrollment->notes = $collection_notes;
            array_push($collection, $enrollment);
        }

        return $collection;
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

    public function getGroupPensum(Request $request)
    {
        if ($request->isGroup == "true") {
            $pensum = DB::table('group_pensum')
                ->where('group_pensum.group_id', '=', $request->group_id)
                ->where('group_pensum.asignatures_id', '=', $request->asignatures_id)
                ->where('group_pensum.schoolyear_id', '=', $request->school_year_id)
                ->get();
        } else {
            $pensum = DB::table('sub_group_pensum')
                ->where('sub_group_pensum.sub_group_id', '=', $request->group_id)
                ->where('sub_group_pensum.asignatures_id', '=', $request->asignatures_id)
                ->where('sub_group_pensum.schoolyear_id', '=', $request->school_year_id)
                ->get();
        }
        return $pensum;
    }

    public function storePerformances(Request $request)
    {
        $params = $request->data;

        $messageExpressions = new MessagesExpressions();
        foreach($params['data'] as $data) {

            if ($data['perfor']) {

                try {
                    $messageExpressions->name = $data['name'];
                    $messageExpressions->institution_id = $params['institution']['id'];
                    $messageExpressions->save();

                } catch (\Exception $e) {
                    $messageExpressions->id = 0;
                }

                $performances = new Performances();
                if ($messageExpressions->id != 0) {
                    try {
                        $performances->pensum_id = $params['performances']['pensum_id'];
                        $performances->evaluation_parameters_id = $params['performances']['evaluation_parameters_id'];
                        $performances->periods_id = $params['performances']['periods_id'];
                        $performances->messages_expressions_id = $messageExpressions->id;
                        $performances->save();
                    } catch (\Exception $e) {
                        $performances->id = 0;
                    }

                }
            }else{
                $messageScale = new MessagesScale();
                if ($messageExpressions->id != 0) {
                    try {
                        $messageScale->name = $data['name'];
                        $messageScale->recommendation = $data['recommendation'];
                        $messageScale->scale_evaluations_id = $data['scale_id'];
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

    public function storeRelationPerformances(Request $request)
    {
        $params = $request->data;
        if ($request->isGroup == "true") {
            $performancesRelation = new NotesParametersPerformances();
            try {
                $performancesRelation->notes_parameters_id = $params['notes_parameters_id'];
                $performancesRelation->performances_id = $params['performances_id'];
                $performancesRelation->periods_id = $params['periods_id'];
                $performancesRelation->group_pensum_id = $params['group_pensum_id'];
                $performancesRelation->save();

            } catch (\Exception $e) {
                $performancesRelation->id = 0;
            }
        } else {
            $performancesRelation = new NotesParametersPerformancesSub();
            try {
                $performancesRelation->notes_parameters_id = $params['notes_parameters_id'];
                $performancesRelation->performances_id = $params['performances_id'];
                $performancesRelation->periods_id = $params['periods_id'];
                $performancesRelation->sub_group_pensum_id = $params['group_pensum_id'];
                $performancesRelation->save();

            } catch (\Exception $e) {
                $performancesRelation->id = 0;
            }

        }
        return $performancesRelation;
    }

    public function getRelationPerformances(Request $request)
    {
        if ($request->isGroup == "true") {
            $notesPerformances = NotesParametersPerformances::where('notes_parameters_id', '=', $request->notes_parameters_id)
                ->where('periods_id', '=', $request->periods_id)
                ->where('group_pensum_id', '=', $request->group_pensum_id)
                ->get();
        } else {
            $notesPerformances = NotesParametersPerformancesSub::where('notes_parameters_id', '=', $request->notes_parameters_id)
                ->where('periods_id', '=', $request->periods_id)
                ->where('sub_group_pensum_id', '=', $request->group_pensum_id)
                ->get();
        }
        return $notesPerformances;
    }

    public function deleteRelationPerformances(Request $request)
    {
        $params = $request->data;
        if ($request->isGroup == "true") {
            $notesPerformances = NotesParametersPerformances::
            where('id', '=', $params['id'])
                ->delete();
        } else {
            $notesPerformances = NotesParametersPerformancesSub::
            where('id', '=', $params['id'])
                ->delete();
        }

        return $notesPerformances;
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


    }
}
