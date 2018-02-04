<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Headquarter extends Model
{
    protected $table = 'headquarter';

    protected $fillable = [
        'id',
    	'name',
    	'nit',
    	'institution_id',
    	'address_id'
    ];


    /**
     * Obtiene la relacion que hay entre direccion y estudiante
     */
    public function address()
    {
        return $this->belongsTo('App\Address', 'address_id');
    }

     /**
     * Obtiene la relacion que hay entre el grupo y la sede
     */
    public function groups()
    {
        return $this->hasMany(Group::class, 'headquarter_id');
    }

    /**
     * Obtiene la relacion que hay entre la matricula y la sede
     */
    public function enrollments()
    {
        return $this->hasMany('App\Enrollment', 'headquarter_id');
    }

    /**
     * Obtiene la relacion que hay entre la Institución y la sede
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }
    /**
     *
     *
     */
    public static function getByInstitution($institution_id)
    {
    	return 	Headquarter::where('institution_id', '=', $institution_id)
    			->get();
    }
}
