<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateEvaluationParameterRequest;

use App\Schoolyear;
use App\EvaluationParameter;
use App\Criteria;

use Auth;

class EvaluationParameterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $institution = Auth::guard('web_institution')->user();

        $parameters = $institution->evaluationParameters()
        ->with('schoolYear')
        ->get();

        return View('institution.partials.evaluationParameter.index')
        ->with('parameters',$parameters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $institution = Auth::guard('web_institution')->user();
        $schoolyears = Schoolyear::orderBy('id', 'ASC')->pluck('year', 'id');

        return View('institution.partials.evaluationParameter.create')
        ->with('schoolyears',$schoolyears)
        ->with('institution',$institution);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEvaluationParameterRequest $request)
    {
        $parameter = new EvaluationParameter($request->all());
        $parameter->save();

        return redirect()->route('evaluationParameter.edit', $parameter);
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
        $parameter = EvaluationParameter::findOrFail($id);
        $schoolyears = Schoolyear::orderBy('id', 'ASC')->pluck('year', 'id');

        return View('institution.partials.evaluationParameter.edit')
        ->with('parameter',$parameter)
        ->with('schoolyears',$schoolyears);
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
        $parameter = EvaluationParameter::findOrFail($id);
        $parameter->fill($request->all());
        $parameter->save();

        return redirect()->route('evaluationParameter.index');
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
