<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Http\Requests\CreatePeriodRequest;

use App\Period;
use App\PeriodState;
use App\PeriodWorkingday;
use App\Schoolyear;
use App\Workingday;
use App\Institution;      

use Auth;

class PeriodController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $institution = Auth::guard('web_institution')->user();

        $periods = $institution->periods()
        ->with('period')
        ->with('schoolyear')
        ->with('state')
        ->with('workingday')
        ->get();

        // return response()->json($periods);
        $periodsPluck = $periods
        ->pluck('period')
        ->unique()
        ->values()
        ->pluck('period','id');
    

        return View('institution.partials.period.index')
        ->with('periods',$periods)
        ->with('periodsPluck',$periodsPluck)
        ->with('institution',$institution);
    }

    public function all(Institution $institution)
    {

        $periods = $institution->periods()
        ->with('period')
        ->with('schoolyear')
        ->with('state')
        ->with('workingday')
        ->get();

        return $this->showAll($periods);   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $schoolyears = Schoolyear::orderBy('id', 'ASC')->pluck('year', 'id');
        $periods = Period::orderBy('name', 'ASC')->pluck('name', 'id');
        $journeys = Workingday::orderBy('name', 'ASC')->get();
        $period_states = PeriodState::orderBy('name', 'ASC')->pluck('name', 'id');

        return View('institution.partials.period.create')
        ->with('schoolyears',$schoolyears)
        ->with('periods',$periods)
        ->with('journeys',$journeys)
        ->with('period_states',$period_states);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePeriodRequest $request)
    {

        // dd($request->working_day_id);
        $institution = Auth::guard('web_institution')->user();
        
        foreach($request->working_day_id['item'] as $key => $workingday_id)
        {
            $period = new PeriodWorkingday($request->all());
            $period->institution_id = $institution->id;
            $period->working_day_id = $workingday_id;
            $period->periods_state_id = 2;
            $period->save();
        }

        flash("Periodo creado con exito")->success();

        return redirect()->route('period.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function showByWorkingday(Institution $institution, $year = '')
    {
        $newYear = ($year == '') ? date("Y") : $year;
        $schoolyear = Schoolyear::where('year','=',$newYear)->first();

        $periods = $institution->periods()
        ->with('period')
        ->with('schoolyear')
        ->with('state')
        ->with('workingday')
        ->where([
            ['working_day_id', '=', $workingday_id],
            ['school_year_id', '=', $schoolyear->id]
        ])
        ->get();


        return $this->showAll($periods);
    }

    public function showByPeriod(Institution $institution, $period_id, $year = '')
    {
        $newYear = ($year == '') ? date("Y") : $year;
        $schoolyear = Schoolyear::where('year','=',$newYear)->first();

        $periods = $institution->periods()
        ->with('period')
        ->with('schoolyear')
        ->with('state')
        ->with('workingday')
        ->where([
            ['period_id', '=', $period_id],
            ['school_year_id', '=', $schoolyear->id]
        ])
        ->get();


        return $this->showAll($periods);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PeriodWorkingday $period)
    {

        $schoolyears = Schoolyear::orderBy('id', 'ASC')->pluck('year', 'id');
        $periods = Period::orderBy('name', 'ASC')->pluck('name', 'id');
        $journeys = Workingday::orderBy('name', 'ASC')->pluck('name', 'id');
        $period_states = PeriodState::orderBy('name', 'ASC')->pluck('name', 'id');

        return View('institution.partials.period.edit')
        ->with('period',$period)
        ->with('schoolyears',$schoolyears)
        ->with('periods',$periods)
        ->with('journeys',$journeys)
        ->with('period_states',$period_states);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $period = PeriodWorkingday::findOrFail($id);
        $period->fill($request->all());
        $period->save();

        flash("Periodo actualizado con exito")->success();

        return redirect()->route('period.index');
    }


    public function changeState(Request $request)
    {
        if($request->ajax())
        {
            $period = PeriodWorkingday::findOrFail($request->value);
            $period->periods_state_id = ($request->checked == "true") ? 1 : 2;
            $period->save();

            return response()->json($period);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $period = PeriodWorkingday::findOrFail($id);
        $period->delete();

        flash("Periodo Eliminado con exito")->success();

        return redirect()->back();
    }
}
