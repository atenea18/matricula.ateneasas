<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use Auth;

use App\Teacher;
use App\Enrollment;
use App\GeneralReport;

class GeneralReportController extends ApiController
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

        $reports = $teacher->groupDirector()
        ->with([
            'enrollments.student', 
            'enrollments.generalReport.periodWorkingday.period',
            'enrollments.group'
        ])
        ->get()
        ->pluck('enrollments')
        ->collapse();

        // return response()->json($reports[0]->generalReport);
        return View('teacher.partials.generalReport.index')
        ->with('teacher',$teacher)
        ->with('groups',$groups)
        ->with('enrollments',$reports);
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
            $report = new GeneralReport($request->all());
            $report->enrollment_id = $enrollment;   
            
            if($report->save())
                array_push($data, $report);
        }

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GeneralReport $generalReport)
    {

        $generalReport->enrollment->student;

        return $this->showOne($generalReport);
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
        $report = GeneralReport::findOrFail($id);
        $report->report = $request->report_edit;
        $report->save();

        return response()->json($report);
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
