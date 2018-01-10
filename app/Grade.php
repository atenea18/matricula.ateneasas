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
}
