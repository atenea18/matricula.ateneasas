<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateScaleEvaluationRequest;
use Auth;

use App\Schoolyear;
use App\WordExpression;
use App\ScaleEvaluation;

class ScaleEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        $institution = Auth::guard('web_institution')->user();

        $scaleEvaluations = $institution->scaleEvaluations()
        ->with('schoolyear')
        ->get();

        // return $scaleEvaluations;
        return View('institution.partials.scaleEvaluation.index')
        ->with('scaleEvaluations', $scaleEvaluations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $schoolyears = Schoolyear::orderBy('id', 'ASC')->pluck('year', 'id');
        $wordExpresions = WordExpression::orderBy('id', 'ASC')->pluck('name', 'id');

        return View('institution.partials.scaleEvaluation.create')
        ->with('schoolyears',$schoolyears)
        ->with('wordExpresions',$wordExpresions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateScaleEvaluationRequest $request)
    {
        $institution = Auth::guard('web_institution')->user();
        $se = new ScaleEvaluation($request->all());

        $institution->scaleEvaluations()->save($se);

        flash("La Escala de valoración ha sido creada con exito")->success();

        return redirect()->route('scaleEvaluation.index');
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
    public function edit(ScaleEvaluation $scaleEvaluation)
    {
        $schoolyears = Schoolyear::orderBy('id', 'ASC')->pluck('year', 'id');
        $wordExpresions = WordExpression::orderBy('id', 'ASC')->pluck('name', 'id');

        // return $scaleEvaluation;
        return View('institution.partials.scaleEvaluation.edit')
        ->with('scaleEvaluation',$scaleEvaluation)
        ->with('schoolyears',$schoolyears)
        ->with('wordExpresions',$wordExpresions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScaleEvaluation $scaleEvaluation)
    {
        $scaleEvaluation->fill($request->all());
        
        // dd($scaleEvaluation);
        $scaleEvaluation->save();

        flash("Se ha actualizado la escala de valoración {$scaleEvaluation->name} con exito")->success();

        return redirect()->route('scaleEvaluation.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScaleEvaluation $scaleEvaluation)
    {
        $scaleEvaluation->delete();

        flash("Se ha eliminado la escala de valoración {$scaleEvaluation->name} con exito")->success();

        return redirect()->route('scaleEvaluation.index');
    }
}
