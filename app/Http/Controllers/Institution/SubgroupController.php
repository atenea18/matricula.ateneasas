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
use App\Enrollment;

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

        $enrollments = $institution->headquarters()
        ->with('subgroups.enrollments.student.identification.identification_type')
        ->with('subgroups.enrollments.group')
        ->get()
        ->pluck('subgroups')
        ->collapse()
        ->where('id', '=', $subgroup->id)
        ->first()
        ->enrollments;

        // return response()->json($enrollments);

        return View('institution.partials.subgroup.edit')
        ->with('subgroup',$subgroup)
        ->with('headquarters',$headquarters)
        ->with('enrollments',$enrollments)
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

    public function assigment(Subgroup $subgroup)
    {
        
        $institution = Auth::guard('web_institution')->user();

        $students_with_subgroup = $institution->headquarters()
        ->with('subgroups')
        ->get()
        ->pluck('subgroups')
        ->collapse()
        ->where('id', '=', $subgroup->id)
        ->first()
        ->enrollments;

        // return response()->json($students_with_subgroup);

        $allEnrollments = $institution->headquarters()
        ->with('groups.enrollments.student')
        ->get()
        ->pluck('groups')
        ->collapse()
        ->pluck('enrollments')
        ->collapse()
        ->where('grade_id', '=', $subgroup->grade_id);

        // dd(count($allEnrollments[1]->subgroups));
        return View('institution.partials.subgroup.assignment')
        ->with('subgroup',$subgroup)
        ->with('students_with_subgroup',$students_with_subgroup)
        ->with('allEnrollments',$allEnrollments);
    }

    public function addEnrollment(Request $request)
    {
        $enrollment = Enrollment::findOrFail($request->enrollment_id);

        $enrollment->subgroups()->attach($request->subgroup_id);

        return response()->json(
            [
                'state' =>  true,
                'message'   =>  'Se ha agregado exitosamente',
                'data'      =>  $enrollment->subgroups
            ]
        );
    }

    public function deleteEnrollment(Request $request)
    {
        $enrollment = Enrollment::findOrFail($request->enrollment_id);

        $enrollment->subgroups()->detach($request->subgroup_id);

        return response()->json(
            [
                'state' =>  true,
                'message'   =>  'Se ha removido del subgrupo exitosamente',
                'data'      =>  $enrollment->subgroups
            ]
        );   
    }
}
