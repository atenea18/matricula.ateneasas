<?php

namespace App;

use App\Helpers\Statistics\ParamsStatistics;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Workingday extends Model
{
    protected $table = 'working_day';

    protected $fillable = [

        'name',
    ];


    /**
     * Obtiene la relacion que hay entre el grupo y la jornada
     */
    public function groups()
    {
        return $this->hasMany('App\Group', 'working_day_id');
    }

    /**
     * Obtiene la relacion que hay entre el periodo y la jornada
     */
    public function periods()
    {
        return $this->hasMany(PeriodWorkingday::class, 'working_day_id');
    }

    public static function getPeriodsByGroup($institution_id, $working_day_id)
    {
        return $periodsWorkingDay = DB::table('working_day_periods')
            ->select(
                'periods.name as periods_name', 'periods.id as periods_id',
                'working_day_periods.start_date', 'working_day_periods.end_date', 'working_day_periods.percent', 'working_day_periods.id as working_day_periods_id',
                'periods_state.name as periods_state_name', 'periods_state.id as periods_state_id'
            )
            ->join('periods_state', 'periods_state.id', '=', 'working_day_periods.periods_state_id')
            ->join('periods', 'periods.id', '=', 'working_day_periods.periods_id')
            ->where('working_day_periods.working_day_id', '=', $working_day_id)
            ->where('working_day_periods.institution_id', '=', $institution_id)
            ->get();
    }

}
