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

    public static function insertRelationPerformances($notes_parameters_id, $performances_id, $periods_id, $group_pensum_id){
        $performancesRelation = new NotesParametersPerformances();

        try {
            $performancesRelation->periods_id = $periods_id;
            $performancesRelation->performances_id = $performances_id;
            $performancesRelation->group_pensum_id = $group_pensum_id;
            $performancesRelation->notes_parameters_id = $notes_parameters_id;
            $performancesRelation->save();

        } catch (\Exception $e) {
            $performancesRelation->id = 0;
        }

        return $performancesRelation;
    }

    public static function getRelationPerformances($notes_parameters_id, $group_pensum_id,$periods_id){
        $notesPerformances = NotesParametersPerformances::where('notes_parameters_id', '=', $notes_parameters_id)
            ->where('periods_id', '=', $periods_id)
            ->where('group_pensum_id', '=', $group_pensum_id)
            ->get();
        return $notesPerformances;
    }

    public static function deleteRelationPerformances($notes_performances_id){
        $notesPerformances =  NotesParametersPerformances::where('id', '=', $notes_performances_id)->delete();
        return $notesPerformances;
    }
}
