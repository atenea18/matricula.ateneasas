<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateSubgroupRequest;

use Auth;

use App\Institution;
use App\Subgroup;
use App\Headquarter;
use App\Grade;

class SubgroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $institution_id = Auth::guard('web_institution')->user()->id;
        $institution = Institution::findOrFail($institution_id);

        $subgroups = $institution->headquarters()
            ->with('subgroups')
            ->with('subgroups.grade')
            ->with('subgroups.headquarter')
            ->get()
            ->pluck('subgroups')
            ->collapse()
            ->sortBy('grade_id');

        return View('institution.partials.subgroup.index')
        ->with('subgroups',$subgroups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $institution = Auth::guard('web_institution')->user();

        $headquarters = Headquarter::where('institution_id', '=', $institution->id)->orderBy('name', 'ASC')->pluck('name', 'id');

        $grades = Grade::orderBy('id', 'ASC')->pluck('name', 'id');

        return View('institution.partials.subgroup.create')
        ->with('headquarters',$headquarters)
        ->with('grades',$grades);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSubgroupRequest $request)
    {
        $subgroup = new Subgroup($request->all());
        $subgroup->save();

        flash("El subgrupo <b>{$subgroup->name}</b> ha sido creado con exito")->success();

        return redirect()->route('subgroup.index');
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
    public function edit(Subgroup $subgroup)
    {

        $institution = Auth::guard('web_institution')->user();

        $headquarters = Headquarter::where('institution_id', '=', $institution->id)->orderBy('name', 'ASC')->pluck('name', 'id');

        $grades = Grade::orderBy('id', 'ASC')->pluck('name', 'id');

        return View('institution.partials.subgroup.edit')
        ->with('subgroup',$subgroup)
        ->with('headquarters',$headquarters)
        ->with('grades',$grades);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subgroup $subgroup)
    {
        $subgroup->fill($request->all());
        $subgroup->save();

        flash("El subgrupo ha sido hactualizo con exito")->success();

        return redirect()->route('subgroup.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subgroup $subgroup)
    {
        $subgroup->delete();

        flash("El subgrupo ha sido eliminado con exito")->success();

        return redirect()->back();
    }

    public function assigment()
    {

    }
}
