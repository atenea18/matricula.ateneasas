<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GroupPensum extends Model
{
    public static function getByGroup($group_id){

        $pensum = DB::table('group_pensum')
            ->select(
                'group_pensum.id',
                'group_pensum.asignatures_id', 'group_pensum.subjects_type_id',
                'group_pensum.areas_id', 'group_pensum.teacher_id',
                'asignatures.name as name_asignatures',
                'subjects_type.name as subjects_type_name',
                DB::raw('CONCAT(managers.last_name," ",managers.name) as name_teachers'),
                'group_pensum.order', 'group_pensum.percent', 'group_pensum.ihs')
            ->join('asignatures', 'asignatures.id', '=', 'group_pensum.asignatures_id')
            ->join('subjects_type', 'subjects_type.id', '=', 'group_pensum.subjects_type_id')
            ->leftJoin('teachers', 'teachers.id', '=', 'group_pensum.teacher_id')
            ->leftJoin('managers', 'managers.id', '=', 'teachers.manager_id')
            ->where('group_pensum.group_id', '=', $group_id)
            ->where('schoolyear_id', '=', 1)
            ->get();
        return $pensum;
    }
}
