<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicLevel extends Model
{
    protected $table = 'academic_level';

    protected $fillable = [
    	'name'
    ];
}
