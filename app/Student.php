<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'student';

    protected $fillable = 
    [
    	'name',
    	'last_name',
    	'identification_id',
    	'address_id',
    	'username',
    	'password',
    	'picture',
    ];

    // 
    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->last_name}";
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
        return  $this->belongsToMany('App\Family', 'family_relationship_student')
                ->withPivot('relationship_id');
    }

    /**
     * Obtiene la relacion que hay entre el estudiante y los familiares
     */
    public function relationship()
    {
        return  $this->belongsToMany('App\RelationShipFamily', 'family_relationship_student')
                ->withPivot('family_id');
    }


    /**
     * Obtiene la relacion que hay entre el estudiante y la matricula
     */
    public function enrollments()
    {
        return $this->hasMany('App\Enrollment', 'student_id');
    }

    public  static function getParents($student_id, $family_student){
        return Student::join('family_relationship_student', 'student.id', '=', 'family_relationship_student.student_id')
            ->select('family_relationship_student.relationship_id')
            ->where('student.id', $student_id)
            ->where('family_relationship_student.family_id', $family_student)
            ->get()[0];

    }

    /**
     * Obtiene todas las relaciones existentes de students
     */
    public static function getInformationsAll($student_id){
        $student = self::findOrfail($student_id);

        $student->identification->identification_type;
        $student->identification->gender;
        $student->identification->city_expedition;
        $student->identification->city_birth;

        $student->address;
        $student->address->zone;

        $student->academicInformation;
        $student->academicInformation->academicCharacter;
        $student->academicInformation->academicSpecialty;

        $student->medicalInformation;
        $student->medicalInformation->eps;
        $student->medicalInformation->blood_type;

        $student->displacement;
        $student->displacement->victimOfConflict;

        $student->socioeconomicInformation;
        $student->socioeconomicInformation->stratum;

        $student->territorialty;

        $student->capacities;
        $student->discapacities;

        $student->family;

        return $student;

    }


    public static function getGroup($group_id)
    {
        return Student::join('enrollment', 'student.id','=','enrollment.student_id')
        ->where('enrollment.group_id','=',$group_id)
        ->orderBy('student.last_name', 'ASC')
        ->get();
    }
}
