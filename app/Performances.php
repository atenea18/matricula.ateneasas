<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Performances extends Model
{
    protected $table = 'performances';

    protected $fillable = [
    	'code', 'pensum_id', 'periods_id', 'evaluation_parameters_id', 'messages_expressions_id'
    ];

    public function noteParameters()
    {
    	return $this->hasMany(NotesParametersPerformances::class, 'performances_id');
    }

    public function message()
    {
    	return $this->belongsTo(MessagesExpressions::class, 'messages_expressions_id');
    }

    public function groupPensum()
    {
        return $this->hasMany(GroupPensumPerformances::class, 'performances_id');
    }
}
