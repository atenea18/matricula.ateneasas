<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Group;
use App\EvaluationPeriod;

class PeriodPendingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $evaPeriod = EvaluationPeriod::where([
            ['enrollment_id', $request->enrollment_id],
            ['periods_id', $request->periods_id],
            ['asignatures_id', $request->asignatures_id]
        ])->get();

        $group = Group::findOrFail($request->group_id);
        $institution = $group->headquarter->institution;
        $period = $institution->periods()
        ->where([
            ['working_day_id', $group->working_day_id],
            ['periods_id', $request->periods_id],
            ['school_year_id', 1],
        ])->first();


        if($period->periods_state_id == 1)
        {

            if(count($evaPeriod) == 0)
            {
                $evalStore = EvaluationPeriod::create($request->all());
                $evalStore->noteFinal()->create([
                    'code_notes_final'  => $evalStore->id,
                    'value'             =>  $request->value
                ]);

                return response()->json([
                    'evalP' =>  $evalStore,
                    'noteFinal' =>  $evalStore->noteFinal
                ]);

            }else{

                $evaPeriod = $evaPeriod->first();

                if(count($evaPeriod->noteFinal()->get()) > 0)
                {
                    $finalNote = $evaPeriod->noteFinal;
                    $finalNote->update($request->all());

                    return response()->json([
                        'evalP' =>  $evaPeriod,
                        'noteFinal' =>  $finalNote,
                    ]);

                }else
                {
                    $evaPeriod->noteFinal()->create([
                        'code_notes_final'  => $evaPeriod->id,
                        'value'             =>  $request->value
                    ]);

                    return response()->json([
                        'evalP' =>  $evaPeriod,
                        'noteFinal' =>  $evaPeriod->noteFinal
                    ]);
                    return "Vamos a crear la nota final";                
                }
            }

        }
        else
        {
            return response()->json([
                'message' => 'El periodo esta desabilitado'
            ], 422);
        }

        return response()->json($evaPeriod);
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
    public function edit($id)
    {
        //
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
