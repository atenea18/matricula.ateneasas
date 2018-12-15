<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'enrollment';

    protected $fillable = [
        'code',
        'folio',
        'school_year_id',
        'student_id',
        'grade_id',
        'enrollment_state_id',
        'institution_id',
    ];


    /**
     * Obtiene la relacion que hay entre el grupo y la matricula
     */
    public function group()
    {
        return $this->belongsToMany(Group::class, 'group_assignment', 'enrollment_id', 'group_id');
    }

    /**
     * Obtiene la relacion que hay entre el subgrupo y la matricula
     */
    public function subgroups()
    {
        return $this->belongsToMany(Subgroup::class, 'sub_group_assignments', 'enrollment_id', 'subgroup_id');
    }

    /**
     * Obtiene la relacion que hay entre el grado y la matricula
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    /**
     * Obtiene la relacion que hay entre el estudiante y la matricula
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Obtiene la relacion que hay entre la matricula y la institución
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    /**
     * Obtiene la relacion que hay entre la matricula y el año lectivo
     */
    public function schoolYear()
    {
        return $this->belongsTo(Schoolyear::class, 'school_year_id');
    }

    /**
     *
     *
     */
    public function finalReportAsignatures()
    {
        return $this->hasMany(FinalReportAsignature::class, 'enrollment_id');
    }

    public function finalReport()
    {
        return $this->hasOne(FinalReport::class, 'enrollment_id');
    }


    /**
    *
    */
    public function attachGroupEnrollment($group_id)
    {
        return $this->group()->attach($group_id, ['created_at' => \Illuminate\Support\Carbon::now()]);   
    }

    public function observations()
    {
        return $this->hasMany(GeneralObservation::class, 'enrollment_id');
    }

    public function generalReport()
    {
        return $this->hasMany(GeneralReport::class, 'enrollment_id');
    }

    public function evaluationPeriod()
    {
        return $this->hasMany(EvaluationPeriod::class,'enrollment_id');
    }

    /**
     *
     *
     */
    public static function getByState($state, $institution_id)
    {
        return Enrollment::join('headquarter', 'enrollment.headquarter_id', '=', 'headquarter.id')
            ->join('institution', 'headquarter.institution_id', '=', 'institution.id')
            ->select('enrollment.*')
            ->where('institution.id', '=', $institution_id)
            ->get();
    }

    public static function getEnrollmentCardGrade($grade_id, $institution_id)
    {
        $enrollments = self::
        join('institution', 'enrollment.institution_id', '=', 'institution.id')
            ->join('headquarter', 'headquarter.institution_id', '=', 'institution.id')
            ->join('student', 'student.id', '=', 'enrollment.student_id')
            ->join('group_assignment', 'enrollment.id', '=', 'group_assignment.enrollment_id')
            ->join('group', 'group_assignment.group_id', '=', 'group.id')
            ->join('grade', 'grade.id', '=', 'group.grade_id')
            ->where('institution.id', $institution_id)
            ->where('grade.id', '=', $grade_id)
            ->orderBy('student.last_name', 'ASC')
            ->get();

        return $enrollments->all();
    }

    public static function getEnrollmentCardGroup($group_id, $institution_id)
    {
        $enrollments = self::
        join('institution', 'enrollment.institution_id', '=', 'institution.id')
            ->join('headquarter', 'headquarter.institution_id', '=', 'institution.id')
            ->join('student', 'student.id', '=', 'enrollment.student_id')
            ->join('group_assignment', 'enrollment.id', '=', 'group_assignment.enrollment_id')
            ->join('group', 'group_assignment.group_id', '=', 'group.id')
            ->join('grade', 'grade.id', '=', 'group.grade_id')
            ->where('institution.id', $institution_id)
            ->where('group.id', '=', $group_id)
            ->orderBy('student.last_name', 'ASC')
            ->get();

        return $enrollments->all();
    }

    public static function getEnrollmentCardStudent($student_id, $institution_id)
    {
        $enrollments = self::
        join('institution', 'enrollment.institution_id', '=', 'institution.id')
            ->join('headquarter', 'headquarter.institution_id', '=', 'institution.id')
            ->join('student', 'student.id', '=', 'enrollment.student_id')
            ->join('group_assignment', 'enrollment.id', '=', 'group_assignment.enrollment_id')
            ->join('group', 'group_assignment.group_id', '=', 'group.id')
            ->join('grade', 'grade.id', '=', 'group.grade_id')
            ->where('enrollment.student_id', $student_id)
            ->get();

        return $enrollments->all();
    }

    public static function existis($school_year_id, $student_id, $institution_id)
    {
        $enrollment = Enrollment::where([
            ['school_year_id', '=', $school_year_id],
            ['student_id', '=', $student_id],
            ['institution_id', '=', $institution_id]
        ])->first();

        return $enrollment;
    }

}
