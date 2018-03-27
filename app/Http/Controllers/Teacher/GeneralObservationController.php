<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use Auth;

use App\Teacher;
use App\Enrollment;
use App\GeneralObservation;

class GeneralObservationController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacher = Auth::guard('teachers')->user()->teachers()->first();

        $groups = $teacher->groupDirector()
        ->get()
        ->pluck('name', 'id');

        $observations = $teacher->groupDirector()
        ->with([
            'enrollments.student', 
            'enrollments.observations',
            'enrollments.observations.periodWorkingday.period',
            'enrollments.group'
        ])
        ->get()
        ->pluck('enrollments')
        ->collapse();

        // return response()->json($observations);  
        return View('teacher.partials.generalObservation.index')
        ->with('teacher',$teacher)
        ->with('groups',$groups)
        ->with('enrollments',$observations );
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

        $data = array();
        foreach($request->enrollments as $key => $enrollment)
        {
            $observation = new GeneralObservation($request->all());
            $observation->enrollment_id = $enrollment;   
            
            if($observation->save())
                array_push($data, $observation);
        }

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GeneralObservation $generalObservation)
    {   
        $generalObservation->enrollment->student;

        // return response()->json($generalObservation);
        return $this->showOne($generalObservation);
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
        $observation = GeneralObservation::findOrFail($id);
        $observation->observation = $request->observation_edit;
        $observation->save();

        return response()->json($observation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GeneralObservation $generalObservation)
    {

        $generalObservation->delete();

        return response()->json($generalObservation);
    }
}
