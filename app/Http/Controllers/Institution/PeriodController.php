<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreatePeriodRequest;

use App\Period;
use App\PeriodState;
use App\PeriodWorkingday;
use App\Schoolyear;
use App\Workingday;

use Auth;

class PeriodController extends Controller
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

        return View('institution.partials.period.index')
        ->with('periods',$periods);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $schoolyears = Schoolyear::orderBy('id', 'ASC')->pluck('year', 'id');
        $periods = Period::orderBy('period', 'ASC')->pluck('period', 'id');
        $journeys = Workingday::orderBy('name', 'ASC')->pluck('name', 'id');
        $period_states = PeriodState::orderBy('state', 'ASC')->pluck('state', 'id');

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
        $institution = Auth::guard('web_institution')->user();

        $period = new PeriodWorkingday($request->all());
        $period->institution_id = $institution->id;
        $period->save();

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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PeriodWorkingday $period)
    {

        $schoolyears = Schoolyear::orderBy('id', 'ASC')->pluck('year', 'id');
        $periods = Period::orderBy('period', 'ASC')->pluck('period', 'id');
        $journeys = Workingday::orderBy('name', 'ASC')->pluck('name', 'id');
        $period_states = PeriodState::orderBy('state', 'ASC')->pluck('state', 'id');

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
