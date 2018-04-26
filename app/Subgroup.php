<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subgroup extends Model
{
    protected $table = 'sub_group';

    protected $fillable = [
    	'name', 'grade_id', 'headquarter_id'
    ];

    /**
     * Obtiene la relacion que hay entre el subgrupo y la sede
     */
    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }

    /**
     * Obtiene la relacion que hay entre el subgrupo y el grado
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

     /**
     * Obtiene la relacion que hay entre el subgrupo y la matricula
     */
    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class, 'sub_group_assignments', 'subgroup_id', 'enrollment_id');
    }

    /**
     * Obtiene la relacion que hay entre el sungrupo y el director de subgrupo
     */
    public function director()
    {
        return $this->belongsToMany(Teacher::class, 'sub_group_directors', 'sub_group_id', 'teacher_id')
                ->withPivot('created_at', 'updated_at');
    }

    public static function enrollmentsBySubGroup($institution_id, $sub_group_id)
    {
        return $enrollments = Enrollment::join('student', 'enrollment.student_id', '=', 'student.id')
            ->select(
                'enrollment.id',
                'student.id as student_id', 'student.name as student_name', 'student.last_name as student_last_name',
                'grade.id as grade_id',
                'sub_group.id as sub_group_id',
                'sub_group_assignments.id as a_id',
                'institution.id as institution_id',
                'schoolyears.id as schoolyears_id'
            )
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
            ->where('sub_group.id', '=', $sub_group_id)
            ->where('institution.id', '=', $institution_id)
            ->where('schoolyears.id', '=', '1')
            ->orderByRaw('student.last_name ASC')
            ->get();
    }
}
