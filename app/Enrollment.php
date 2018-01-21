<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'enrollment';

    protected $fillable = [
        'student_id',
        'group_id',
        'enrollment_state_id',
        'enrollment_result_id',
        'headquarter_id',
    ];


    /**
     * Obtiene la relacion que hay entre el grupo y la matricula
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * Obtiene la relacion que hay entre el estudiante y la matricula
     */
    public function student()
    {
        return $this->belongsTo('App\Student', 'student_id');
    }

    /**
     * Obtiene la relacion que hay entre la matricula y la sede
     */
    public function headquarter()
    {
        return $this->belongsTo('App\Headquarter', 'headquarter_id');
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
        join('headquarter', 'enrollment.headquarter_id', '=', 'headquarter.id')
            ->join('institution', 'headquarter.institution_id', '=', 'institution.id')
            ->join('student', 'student.id', '=', 'enrollment.student_id')
            ->join('group', 'group.id', '=', 'enrollment.group_id')
            ->join('grade', 'grade.id', '=', 'group.grade_id')
            ->where('institution.id', $institution_id)
            ->where('grade.id', '=', $grade_id)
            ->get();

        return $enrollments->all();
    }

    public static function getEnrollmentCardGroup($group_id, $institution_id)
    {
        $enrollments = self::
        join('headquarter', 'enrollment.headquarter_id', '=', 'headquarter.id')
            ->join('institution', 'headquarter.institution_id', '=', 'institution.id')
            ->join('student', 'student.id', '=', 'enrollment.student_id')
            ->join('group', 'group.id', '=', 'enrollment.group_id')
            ->join('grade', 'grade.id', '=', 'group.grade_id')
            ->where('institution.id', $institution_id)
            ->where('group.id', '=', $group_id)
            ->get();

        return $enrollments->all();
    }

}
