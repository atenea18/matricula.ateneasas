<?php

namespace App\Http\Controllers;

use App\Asignature;
use App\FinalReportAsignature;
use App\Grade;
use App\Group;
use App\GroupPensum;
use App\Helpers\Utils\Utils;
use App\NoAttendance;
use App\Note;
use App\NotesFinal;
use App\Pensum;
use App\Period;
use App\Teacher;
use App\Workingday;
use App\EvaluationPeriod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
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
                $this->teacher = null;
                $this->institution = Auth::guard('web_institution')->user();
            }
            return $next($request);
        });
    }

    public function getCollectionsNotes(Request $request)
    {
        $enrollments = Group::enrollmentsByGroup($this->institution->id, $request->group_id);

        $report_asignatures = FinalReportAsignature::getEnrollmentsByGroupAsignatures($request->group_id, $request->asignature_id);

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
            ->where('group.id', '=', $request->group_id)
            ->where('institution.id', '=', $this->institution->id)
            ->where('schoolyears.id', '=', '1')
            ->where('evaluation_periods.asignatures_id', '=', $request->asignature_id)
            ->where('evaluation_periods.periods_id', '=', $request->period_id)
            ->get();

        $notes_final = DB::table('notes_final')
            ->select('enrollment.id as enrollment_id', 'notes_final.value', 'notes_final.overcoming',
                'notes_final.id as notes_final_id', 'evaluation_periods.asignatures_id', 'evaluation_periods.periods_id',
                'evaluation_periods.id as evaluation_periods_id')
            ->join('evaluation_periods', 'evaluation_periods.id', '=', 'notes_final.evaluation_periods_id')
            ->join('enrollment', 'enrollment.id', '=', 'evaluation_periods.enrollment_id')
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
            ->where('group.id', '=', $request->group_id)
            ->where('institution.id', '=', $this->institution->id)
            ->where('evaluation_periods.asignatures_id', '=', $request->asignature_id)
            ->where('schoolyears.id', '=', '1')
            ->where('evaluation_periods.periods_id', '=', $request->period_id)
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
            ->where('group.id', '=', $request->group_id)
            ->where('institution.id', '=', $this->institution->id)
            ->where('schoolyears.id', '=', '1')
            ->where('evaluation_periods.asignatures_id', '=', $request->asignature_id)
            ->where('evaluation_periods.periods_id', '=', $request->period_id)
            ->get();



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
            

            if (count($report_asignatures) >= 1) {
                foreach ($report_asignatures as $key_report => $report) {
                    if ($enrollment->id == $report->enrollment_id) {
                        $enrollment->report_asignature = $report;
                        unset($report_asignatures[$key_report]);
                    }
                }
            }

            foreach ($notes_final as $keyNotes => $note) {
                if ($enrollment->id == $note->enrollment_id) {
                    $enrollment->notes_final = $note;
                    unset($notes_final[$keyNotes]);
                }
            }

            //$enrollment->notes_final = $collection_notes_final;
            $enrollment->notes = $collection_notes;
            array_push($collection, $enrollment);
        }

        return $collection;
    }

    public function index($group_id, $asignature_id = null)
    {


        $areas = $this->dispatcherAreas();
        $asignatures = $this->dispatcherSubject();
        $grades = $this->dispatcherGrades();
        $groups = $this->dispatcherGroups();

        $vectorGrades = $this->merge($grades, $groups, $areas, $asignatures);

        $group_selected = Group::getGroupsById($group_id);
        $grade_selected = Grade::find($group_selected->grade_id);
        $asignature_selected = Asignature::getAsignaturesById($asignature_id);
        $area_selected = $this->dispatcherAreaSelected($grade_selected, $asignature_selected);

        $vector = (object)array(
            'grades' => $vectorGrades,
            'group_selected' => $group_selected,
            'grade_selected' => $grade_selected,
            'asignature_selected' => $asignature_selected,
            'area_selected' => $area_selected,
            'teacher_selected' => $this->teacher
        );

        return View('institution.partials.evaluation.period.index')
            ->with('data_evaluation', $vector);

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
                $notesFinal->evaluation_periods_id = $data['evaluation_periods_id'];
                $notesFinal->save();

            } catch (\Exception $e) {

            }
        } else {
            NotesFinal::where('evaluation_periods_id', '=', $data['evaluation_periods_id'])
                ->update([
                    'code_notes_final' => $data['evaluation_periods_id'],
                    'value' => $data['value'],
                ]);
        }

        $notesFinal = NotesFinal::where('evaluation_periods_id', '=', $data['evaluation_periods_id'])
            ->first();

        return $notesFinal;

    }

    public function store(request $request)
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

    public function storeNote(request $request)
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

    private function merge($grades, $groups, $areas, $asignatures)
    {
        foreach ($grades as $grade) {
            $ff = [];
            foreach ($groups as $key_group => $group) {

                if ($group->grade_id == $grade->id) {
                    $tt = [];
                    //se recorre areas
                    foreach ($areas as $key_area => $area) {
                        $vec = [];

                        if ($area->group_id == $group->group_id) {

                            foreach ($asignatures as $key_asignature => $asignature) {
                                if ($asignature->area_id == $area->area_id && $asignature->group_id == $group->group_id) {

                                    array_push($vec, $asignature);
                                    unset($asignatures[$key_asignature]);
                                }
                            }
                            if (!isset($area->vectorAsignatures))
                                $area->vectorAsignatures = [];
                            $area->vectorAsignatures = $vec;


                            array_push($tt, $area);
                            unset($areas[$key_area]);
                        }
                    }
                    if (!isset($group->vectorAreas))
                        $group->vectorAreas = [];
                    $group->vectorAreas = $tt;

                    array_push($ff, $group);
                    unset($groups[$key_group]);
                }
                if (!isset($grade->vectorGroups))
                    $grade->vectorGroups = [];
                $grade->vectorGroups = $ff;
            }
        }
        return $grades;
    }

    private function dispatcherSubject()
    {
        if ($this->teacher) {
            return $asignatures = GroupPensum::getAsignaturesByTeacherInstitution($this->institution->id, $this->teacher->id);
        } else {
            return $asignatures = GroupPensum::getAsignaturesByInstitution($this->institution->id);
        }
    }

    private function dispatcherAreas()
    {
        if ($this->teacher) {
            return $areas = GroupPensum::getAreasByTeacherInstitution($this->teacher->id);
        } else {
            return $areas = GroupPensum::getAreasByInstitution($this->institution->id);
        }
    }

    private function dispatcherGroups()
    {

        if ($this->teacher) {
            return $groups = Group::getGroupsByTeacherInstitution($this->teacher->id);
        } else {
            return $groups = Group::getGroupsByInstitution($this->institution->id);
        }
    }

    private function dispatcherGrades()
    {

        if ($this->teacher) {
            return $grades = Group::getGradeByTeacher($this->teacher->id);
        } else {
            return $grades = Grade::all();
        }
    }

    private function dispatcherAreaSelected($grade, $asignature)
    {
        if ($this->teacher) {
            return Pensum::getAreaByAsignatureOfPensum($grade->id, $asignature->id, $this->institution->id);
        } else {
            return null;
        }
    }
}
