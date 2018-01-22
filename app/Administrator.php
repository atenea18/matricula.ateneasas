<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

//Trait for sending notifications in laravel
use Illuminate\Notifications\Notifiable;

class Administrator extends Authenticatable
{

	// This trait has notify() method defined
  	use Notifiable;
  	
    //Mass assignable attributes
	protected $fillable = [
	    'name', 'email', 'password',
	];

	//hidden attributes
	protected $hidden = [
	    'password', 'remember_token',
	];
}
