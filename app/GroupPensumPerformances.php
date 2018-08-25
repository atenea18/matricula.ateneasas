<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupPensumPerformances extends Model
{
    protected $table = 'group_pensum_performances';

    protected $fillable = [
        'code', 'performances_id', 'group_pensum_id', 'periods_id'       
    ]; 
    
    public function groupPensum()
    {
        return $this->belongsTo(GroupPensum::class, 'group_pensum_id');
    }

    public function performance()
    {
        return $this->belongsTo(Performances::class, 'performances_id');
    }

    public static function insertRelationPerformances($performances_id, $periods_id, $group_pensum_id)
    {
        $performancesRelation = new GroupPensumPerformances();
        try {
            $performancesRelation->periods_id = $periods_id;
            $performancesRelation->performances_id = $performances_id;
            $performancesRelation->group_pensum_id = $group_pensum_id;
            $performancesRelation->save();

        } catch (\Exception $e) {
            $performancesRelation->id = 0;
        }
        return $performancesRelation;
    }

    public static function getRelationPerformances($group_pensum_id, $periods_id)
    {
        $performancesRelation = GroupPensumPerformances::select(
            'group_pensum_performances.id', 'messages_expressions.name', 'group_pensum_performances.performances_id'
        )
            ->join('performances', 'performances.id', '=', 'group_pensum_performances.performances_id')
            ->join('messages_expressions', 'messages_expressions.id', '=', 'performances.messages_expressions_id')
            ->where('group_pensum_performances.periods_id', '=', $periods_id)
            ->where('group_pensum_performances.group_pensum_id', '=', $group_pensum_id)
            ->get();
        return $performancesRelation;
    }

    public static function deleteRelationPerformances($group_performances_id)
    {
        $performancesRelation = GroupPensumPerformances::where('id', '=', $group_performances_id)->delete();
        return $performancesRelation;
    }

}
