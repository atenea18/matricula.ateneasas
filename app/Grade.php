<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grade';

    protected $fillable = [
        'name',
        'academic_level',
    ];

    /**
     * Obtiene la relacion que hay entre el grupo y el grado
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'grade_id');
    }

    /**
     * Obtiene la relacion que hay entre el grado y la matricula
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'grade_id');
    }

    public static function enrollmentsByGrade($institution_id, $group_id)
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

}
