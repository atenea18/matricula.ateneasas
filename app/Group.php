<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Period;
use App\Asignature;

class Group extends Model
{

    protected $table = 'group';

    protected $fillable = [
        'id',
        'name',
        'quota',
        'headquarter_id',
        'grade_id',
        'modality',
        'type',
        'working_day_id'
    ];

    /**
     * Obtiene la relacion que hay entre el grupo y la matricula
     */
    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class, 'group_assignment', 'group_id', 'enrollment_id');
    }

    /**
     * Obtiene la relacion que hay entre el docente y los grupos la asignatura (Pensum)
     */
    public function pensums()
    {
        return $this->belongsTo(GroupPensum::class, 'group_id'); 
    }

    /**
     * Obtiene la relacion que hay entre el docente y los grupos del pensums
     */
    // public function teachers()
    // {
    //     return $this->belongsToMany(Group::class, 'group_pensum', 'group_id', 'teacher_id');
    // }

    /**
     * Obtiene la relacion que hay entre el grupo y la jornada
     */
    public function workingday()
    {
        return $this->belongsTo('App\Workingday', 'working_day_id');
    }

    /**
     * Obtiene la relacion que hay entre el grupo y la sede
     */
    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }

    /**
     * Obtiene la relacion que hay entre el grupo y el grado
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    /**
     * Obtiene la relacion que hay entre el grupo y el director de grupo
     */
    public function director()
    {
        return $this->belongsToMany(Teacher::class, 'group_directors', 'group_id', 'teacher_id')
                ->withPivot('created_at', 'updated_at');
    }

    /**
    *
    */
    public function synchDirector($teacher_id)
    {
        return $this->director()->sync($teacher_id, ['created_at' => \Illuminate\Support\Carbon::now()]);   
    }

    public static function getAllByInstitution($institution_id)
 	{
 		return Group::join('headquarter', 'group.headquarter_id', '=', 'headquarter.id')
 			->join('institution', 'headquarter.institution_id', '=', 'institution.id')
 			->select('group.*')
 			->where('institution.id', '=', $institution_id)
 			->orderBy('group.grade_id')
 			->get();
 	}

 	public static function enrollmentsWithOutGroup($institution_id, $grade_id){
        $enrollments = Enrollment::join('student', 'enrollment.student_id', '=', 'student.id')
            ->select(
                'enrollment.id'
            )
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
            ->where('grade.id', '=', $grade_id)
            ->where('institution.id', '=', $institution_id)
            ->where('schoolyears.id', '=', '1')
            ->orderByRaw('student.last_name ASC')
            ->get();

        return $enrollmentsWithOutGroup = DB::table('enrollment')
            ->join('student', 'enrollment.student_id', '=', 'student.id')
            ->select(
                'enrollment.id',
                'student.id as student_id', 'student.name as student_name', 'student.last_name as student_last_name',
                'grade.id as grade_id',
                'institution.id as institution_id',
                'schoolyears.id as schoolyears_id'
            )
            ->join('grade', 'enrollment.grade_id', '=', 'grade.id')
            ->join('institution', 'enrollment.institution_id', '=', 'institution.id')
            ->join('schoolyears', 'enrollment.school_year_id', 'schoolyears.id')
            ->where('institution.id', '=', $institution_id)
            ->where('schoolyears.id', '=', '1')
            ->orderByRaw('student.last_name ASC')
            ->where('grade.id', '=', $grade_id)
            ->whereNotIn('enrollment.id', $enrollments)
            ->get();


    }

    public static function enrollmentsByGroup($institution_id, $group_id)
    {
        return $enrollments = Enrollment::join('student', 'enrollment.student_id', '=', 'student.id')
            ->select(
                'enrollment.id',
                'student.id as student_id', 'student.name as student_name', 'student.last_name as student_last_name',
                'grade.id as grade_id',
                'group.id as group_id',
                'group_assignment.id as a_id',
                'institution.id as institution_id',
                'schoolyears.id as schoolyears_id'
            )
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
            ->where('group.id', '=', $group_id)
            ->where('institution.id', '=', $institution_id)
            ->where('schoolyears.id', '=', '1')
            ->orderByRaw('student.last_name ASC')
            ->get();
    }

    public static function  getGroupsByGrade($institution_id, $grade_id)
    {
        return $groups = Group::join('headquarter', 'headquarter.id', '=', 'group.headquarter_id')
            ->select('group.id','group.name', 'headquarter.name as headquarter_name')
            ->where('headquarter.institution_id','=',$institution_id)
            ->where('grade_id', '=', $grade_id)->get();
    }

    public function recovery(Asignature $asignature, Period $period, ScaleEvaluation $scale)
    {
        return $this->select(DB::raw('CONCAT(student.last_name, " ", student.name) AS name_student'), 'notes_final.overcoming', 'notes_final.value', 'notes_final.id AS note_final_id')
                ->join('group_assignment', 'group.id', '=', 'group_assignment.group_id')
                ->join('enrollment', 'group_assignment.enrollment_id', '=', 'enrollment.id')
                ->join('student', 'student.id', '=', 'enrollment.student_id')
                ->join('evaluation_periods', 'enrollment.id', '=', 'evaluation_periods.enrollment_id')
                ->join('notes_final', 'evaluation_periods.id', '=', 'notes_final.evaluation_periods_id')
                ->where([
                    ['enrollment.school_year_id', '=', 1],
                    ['group.id', '=', $this->id],
                    ['evaluation_periods.asignatures_id', '=', $asignature->id],
                    ['evaluation_periods.periods_id', '=', $period->id],
                ])
                ->where([
                    ['notes_final.value', '>=', $scale->rank_start],
                    ['notes_final.value', '<=', $scale->rank_end]
                ])
                ->get();
    }

    public function pendingPeriod(Asignature $asignature, Period $period, ScaleEvaluation $scale)
    {

    }

}
