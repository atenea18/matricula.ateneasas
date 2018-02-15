<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

//Trait for sending notifications in laravel
use Illuminate\Notifications\Notifiable;

//Notification for Institution
use App\Notifications\InstitutionResetPasswordNotification;

class Institution extends Authenticatable
{	

	// This trait has notify() method defined
  	use Notifiable;

	protected $table = 'institution';

    //Mass assignable attributes
	protected $fillable = [
	    'id', 'name', 'dane_code', 'picture', 'email', 'password',
	];

	//hidden attributes
	protected $hidden = [
	    'password', 'remember_token',
	];

	/**
     * Obtiene la relacion que hay entre la Institución y la sede
     */
    public function headquarters()
    {
        return $this->hasMany(Headquarter::class, 'institution_id');
    }

    /**
     * Obtiene la relacion que hay entre la Institución y los docentes
     */
    public function teachers()
    {
    	return $this->hasMany(Teacher::class, 'institution_id');
    }

    /**
     * Obtiene la relacion que hay entre la matricula y la institución
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'institution_id');
    }

	//Send password reset notification
	public function sendPasswordResetNotification($token)
	{
	    $this->notify(new InstitutionResetPasswordNotification($token));
	}
}
