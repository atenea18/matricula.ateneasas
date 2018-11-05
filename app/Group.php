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
        return $this->hasMany(GroupPensum::class, 'group_id');
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

    public static function enrollmentsWithOutGroup($institution_id, $grade_id)
    {
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
        return $enrollments = DB::table('enrollment')->
        join('student', 'enrollment.student_id', '=', 'student.id')
            ->select(
                'enrollment.id',
                'student.id as student_id', 'student.name as student_name', 'student.last_name as student_last_name',
                'grade.id as grade_id',
                'group.id as group_id',
                'group_assignment.id as a_id',
                'institution.id as institution_id',
                'schoolyears.id as schoolyears_id',
                'news.id as news_id',
                'news.name as news_name',
                'enrollment_news.id as enrollment_news_id',
                'enrollment_news.date'
            )
            ->leftJoin('enrollment_news', 'enrollment_news.enrollment_id', '=', 'enrollment.id')
            ->leftJoin('news', 'news.id', '=', 'enrollment_news.news_id')
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

    public static function getGroupsByGrade($institution_id, $grade_id)
    {
        return Group::
        select('group.id', 'group.name',
            DB::raw("UPPER(headquarter.name) as headquarter_name"), 'grade.name as grade_name',
            'group.grade_id', 'group.working_day_id',
            DB::raw("UPPER(working_day.name) as working_day_name"), 'group.working_day_id',
            DB::raw("UPPER(CONCAT(managers.name,' ',managers.last_name)) as director_name")
        )
            ->join('headquarter', 'headquarter.id', '=', 'group.headquarter_id')
            ->leftJoin('group_directors', 'group_directors.group_id', '=', 'group.id')
            ->leftJoin('teachers', 'teachers.id', '=', 'group_directors.teacher_id')
            ->leftJoin('managers', 'managers.id', '=', 'teachers.manager_id')
            ->join('institution', 'institution.id', '=', 'headquarter.institution_id')
            ->join('working_day', 'working_day.id', '=', 'group.working_day_id')
            ->join('grade', 'grade.id', '=', 'group.grade_id')
            ->where('institution.id', '=', $institution_id)
            ->where('group.grade_id', '=', $grade_id)
            ->get();
    }

    public static function getGroupsById($group_id)
    {
        return $groups = Group::
        select('group.id', 'group.name',
            DB::raw("UPPER(headquarter.name) as headquarter_name"), 'grade.name as grade_name',
            'group.grade_id', 'group.working_day_id',
            DB::raw("UPPER(working_day.name) as working_day_name"), 'group.working_day_id',
            DB::raw("UPPER(CONCAT(managers.name,' ',managers.last_name)) as director_name")
        )
            ->join('headquarter', 'headquarter.id', '=', 'group.headquarter_id')
            ->leftJoin('group_directors', 'group_directors.group_id', '=', 'group.id')
            ->leftJoin('teachers', 'teachers.id', '=', 'group_directors.teacher_id')
            ->leftJoin('managers', 'managers.id', '=', 'teachers.manager_id')
            ->join('institution', 'institution.id', '=', 'headquarter.institution_id')
            ->join('working_day', 'working_day.id', '=', 'group.working_day_id')
            ->join('grade', 'grade.id', '=', 'group.grade_id')
            ->where('group.id', '=', $group_id)
            ->get()
            ->first();
    }

    public static function getGroupsByTeacherInstitution($teacher_id)
    {
        return $groups = Group::select('group.id as group_id', 'group.name as group_name',
            DB::raw("UPPER(headquarter.name) as headquarter_name"),
            'grade.name as grade_name', 'group.grade_id', 'group.working_day_id',
            DB::raw("UPPER(working_day.name) as working_day_name"), 'group.working_day_id',
            DB::raw("UPPER(CONCAT(managers.name,' ',managers.last_name)) as director_name")
        )
            ->join('headquarter', 'headquarter.id', '=', 'group.headquarter_id')
            ->leftJoin('group_directors', 'group_directors.group_id', '=', 'group.id')
            ->leftJoin('teachers', 'teachers.id', '=', 'group_directors.teacher_id')
            ->leftJoin('managers', 'managers.id', '=', 'teachers.manager_id')
            ->join('group_pensum', 'group_pensum.group_id', '=', 'group.id')
            ->join('institution', 'institution.id', '=', 'headquarter.institution_id')
            ->join('working_day', 'working_day.id', '=', 'group.working_day_id')
            ->join('grade', 'grade.id', '=', 'group.grade_id')
            ->where('group_pensum.teacher_id', '=', $teacher_id)
            ->groupBy('group_pensum.group_id')
            ->orderBy('grade.id')
            ->get();
    }

    public static function getGroupsByInstitution($institution_id)
    {
        return $groups = Group::select('group.id as group_id', 'group.name as group_name',
            DB::raw("UPPER(headquarter.name) as headquarter_name"),
            'grade.name as grade_name', 'group.grade_id', 'group.working_day_id',
            DB::raw("UPPER(working_day.name) as working_day_name"), 'group.working_day_id',
            DB::raw("UPPER(CONCAT(managers.name,' ',managers.last_name)) as director_name")
        )
            ->join('headquarter', 'headquarter.id', '=', 'group.headquarter_id')
            ->leftJoin('group_directors', 'group_directors.group_id', '=', 'group.id')
            ->leftJoin('teachers', 'teachers.id', '=', 'group_directors.teacher_id')
            ->leftJoin('managers', 'managers.id', '=', 'teachers.manager_id')
            ->join('group_pensum', 'group_pensum.group_id', '=', 'group.id')
            ->join('institution', 'institution.id', '=', 'headquarter.institution_id')
            ->join('working_day', 'working_day.id', '=', 'group.working_day_id')
            ->join('grade', 'grade.id', '=', 'group.grade_id')
            ->where('institution.id', '=', $institution_id)
            ->groupBy('group_pensum.group_id')
            ->orderBy('grade.id')
            ->get();
    }

    public static function getGradeByTeacher($teacher_id)
    {
        return $groups = Group::select('grade.id', 'grade.name')
            ->join('headquarter', 'headquarter.id', '=', 'group.headquarter_id')
            ->join('group_pensum', 'group_pensum.group_id', '=', 'group.id')
            ->join('institution', 'institution.id', '=', 'headquarter.institution_id')
            ->join('working_day', 'working_day.id', '=', 'group.working_day_id')
            ->join('grade', 'grade.id', '=', 'group.grade_id')
            ->where('group_pensum.teacher_id', '=', $teacher_id)
            ->groupBy('group.grade_id')
            ->orderBy('grade.id')
            ->get();
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
