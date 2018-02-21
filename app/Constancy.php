<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Constancy extends Model
{
    protected $fillable = [
    	'header',
        'content',
        'footer',
        'firstFirm',
        'firstRol',
        'secondFirm',
        'secondRol',
    	'type_id',
    	'institution_id'
    ];

    public function type()
    {
    	return $this->belongsTo(Constancy_type::class, 'type_id');
    }

    public function institution()
    {
    	return $this->belongsTo(Institution::class, 'institution_id');
    }
}
