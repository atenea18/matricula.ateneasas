<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'student';

    protected $fillable =
        [
            'id',
            'name',
            'last_name',
            'identification_id',
            'address_id',
            'username',
            'password',
            'picture',
            'state_id'
        ];

    /**
     * Obtiene la relacion que hay entre el estado y estudiante
     */
    public function state()
    {
        return $this->belongsTo(StudentState::class, 'state_id');
    }


    /**
     * Obtiene la relacion que hay entre identificacion y estudiante
     */
    public function identification()
    {
        return $this->belongsTo('App\Identification', 'identification_id');
    }

    /**
     * Obtiene la relacion que hay entre direccion y estudiante
     */
    public function address()
    {
        return $this->belongsTo('App\Address', 'address_id');
    }

    /**
     * Obtiene la relacion que hay entre el estudiante y la información academica
     */
    public function academicInformation()
    {
        return $this->hasOne('App\AcademicInformation', 'student_id');
    }

    /**
     * Obtiene la relacion que hay entre el estudiante y la información medica
     */
    public function medicalInformation()
    {
        return $this->hasOne('App\MedicalInformation', 'student_id');
    }

    /**
     * Obtiene la relacion que hay entre el estudiante y la información de desplazamiento
     */
    public function displacement()
    {
        return $this->hasOne('App\Displacement', 'student_id');
    }

    /**
     * Obtiene la relacion que hay entre el estudiante y la información de socioeconomica
     */
    public function socioeconomicInformation()
    {
        return $this->hasOne('App\SocioeconomicInformation', 'student_id');
    }

    /**
     * Obtiene la relacion que hay entre el estudiante y la territorialidad
     */
    public function territorialty()
    {
        return $this->hasOne('App\Territorialty', 'student_id');
    }

    /**
     * Obtiene la relacion que hay entre el estudiante y las capacidades
     */
    public function capacities()
    {
        return $this->belongsToMany('App\Capacity');
    }

    /**
     * Obtiene la relacion que hay entre el estudiante y las discapacidades
     */
    public function discapacities()
    {
        return $this->belongsToMany('App\Discapacity');
    }

    /**
     * Obtiene la relacion que hay entre el estudiante y los familiares
     */
    public function family()
    {
        return $this->belongsToMany(Family::class, 'family_relationship_student')
            ->withPivot('relationship_id');
    }

    /**
     * Obtiene la relacion que hay entre el estudiante y los familiares
     */
    public function relationship()
    {
        return $this->belongsToMany('App\RelationShipFamily', 'family_relationship_student')
            ->withPivot('family_id');
    }


    /**
     * Obtiene la relacion que hay entre el estudiante y la matricula
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    // 
    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->last_name}";
    }

    public function getFullNameInverseAttribute()
    {
        return "{$this->last_name} {$this->name}";
    }

    public static function getParents($student_id, $family_student)
    {
        return Student::join('family_relationship_student', 'student.id', '=', 'family_relationship_student.student_id')
            ->select('family_relationship_student.relationship_id')
            ->where('student.id', $student_id)
            ->where('family_relationship_student.family_id', $family_student)
            ->get()[0];

    }

    /**
     * Obtiene todas las relaciones existentes de students
     */

    public static function existStudent()
    {

         if (old('identification_number')) {
             if (isset(\App\Identification::where('identification_number', '=', old('identification_number'))->get()[0])) {
                 $identification_id = Identification::where('identification_number', '=', old('identification_number'))->get()[0]->id;

                 $student = Student::where('identification_id', '=', $identification_id)->first();


                 if(!is_null($student))
                 {

                    $enrollment = Enrollment::where([
                        ['student_id', '=', $student->id],
                        ['school_year_id', '=', 1]
                    ])->first();

                    if (!is_null($enrollment)) {

                         return '<h4 style="text-align: center"> El estudiante con número de identificación 
                             <b>' . old('identification_number') . '</b> ya está matriculado en '. '
                             <a href="' . route('enrollment.edit', $enrollment->id) . '"> ver estudiante</a></h4>';

                     }


                     return '
                     <h4 style="text-align: center"> El estudiante con número de identificación <b>' . old('identification_number')
                         . '</b> ya existe, pero <b>NO ESTA MATRICULADO.</b>
                     <a href="' . route('enrollment.create', $student->id) . '">Completar Matrícula</a></h4>';
                }

             }

         }

         return '';

    }


    public static function getGroup($group_id)
    {
        return Student::join('enrollment', 'student.id', '=', 'enrollment.student_id')
            ->where('enrollment.group_id', '=', $group_id)
            ->orderBy('student.last_name', 'ASC')
            ->get();
    }
}
