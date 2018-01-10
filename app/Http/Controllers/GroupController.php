<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;
use App\Http\Requests\CreateGroupRequest;

// MODELS
use App\Headquarter;
use App\Grade;
use App\Workingday;
use App\Group;

class GroupController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        $groups = Group::getAllByInstitution($institution_id);

        $groups->each(function($groups){

            $groups->workingday;
            $groups->headquarter;
        });

        // dd($institution_id);

        return view('institution.partials.group.index')
                ->with('groups', $groups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $institution_id = Auth::guard('web_institution')->user()->id;

        $headquarters = Headquarter::where('institution_id', '=', $institution_id)->orderBy('name', 'ASC')->pluck('name', 'id');

        
        $grades = Grade::orderBy('id', 'ASC')->pluck('name', 'id');
        $journeys = Workingday::orderBy('id', 'ASC')->pluck('name', 'id');
        

        return view('institution.partials.group.create')
                ->with('headquarters', $headquarters)
                ->with('grades', $grades)
                ->with('journeys', $journeys);
    }

	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateGroupRequest $request)
    {
        
        $group = new Group($request->all());
        $group->save();

        return redirect()->route('group.index');
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
        $institution_id = Auth::guard('web_institution')->user()->id;

        $headquarters = Headquarter::where('institution_id', '=', $institution_id)->orderBy('name', 'ASC')->pluck('name', 'id');

        
        $grades = Grade::orderBy('id', 'ASC')->pluck('name', 'id');
        $journeys = Workingday::orderBy('id', 'ASC')->pluck('name', 'id');

        $group = Group::findOrFail($id);

        return view('institution.partials.group.edit')
                ->with('headquarters', $headquarters)
                ->with('grades', $grades)
                ->with('journeys', $journeys)
                ->with('group', $group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateGroupRequest $request, $id)
    {
        $group = Group::findOrFail($id);

        $group->fill($request->all());
        $group->save();

        return redirect()->route('group.index');
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


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getByWorkingDay(Request $request)
    {
        if($request->ajax()):

            $response = Group::where([
                ['working_day_id', '=', $_GET['workingday_id']],
                ['headquarter_id', '=', $_GET['headquarter_id']]
            ])->get();

            return response()->json($response);

        endif;
    }
}
