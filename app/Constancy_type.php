<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Constancy_type extends Model
{
    protected $fillable = [
    	'name'
    ];

    public function constancies()
    {
    	return $this->hasMany(Constancy::class, 'type_id');
    }
}
