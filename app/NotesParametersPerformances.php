<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotesParametersPerformances extends Model
{
    protected $table = 'notes_parameters_performances';

    protected $fillable = [
    	'code', 'notes_parameters_id', 'performances_id', 'group_pensum_id', 'periods_id'
    ];

    public function parameterNote()
    {
    	return $this->belongsTo(NotesParameters::class, 'notes_parameters_id');
    }

    public function performance()
    {
    	return $this->belongsTo(Performances::class, 'performances_id');
    }

    public function groupPensum()
    {
        return $this->belongsTo(GroupPensum::class, 'group_pensum_id');
    }
}
