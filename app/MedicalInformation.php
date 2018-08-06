<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalInformation extends Model
{
    protected $table = 'medical_information';

    protected $fillable =
    [
        'id',
    	'ips',
    	'ars',
    	'student_id',
    	'eps_id',
    	'blood_type_id',
    ];

    /**
     * Obtiene la relacion que hay entre el estudiante y la información medica
     */
    public function student()
    {
        return $this->hasOne('App\Student', 'student_id');
    }

    /**
     * Obtiene la relacion que hay entre la información medica y la eps
     */
    public function eps()
    {
        return $this->belongsTo('App\Eps', 'eps_id');
    }

    /**
     * Obtiene la relacion que hay entre la información medica y el tipo de sangre
     */
    public function blood_type()
    {
        return $this->belongsTo('App\BloodType', 'blood_type_id');
    }
}
