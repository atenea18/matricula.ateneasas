<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Constancy_type;
use App\Constancy;
use App\Workingday;
use App\Grade;

use Auth;

class ConstancyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $institution = Auth::guard('web_institution')->user();

        $constancies = $institution->constancies()
        ->with('type')
        ->get();

        return view('institution.partials.constancy.index')
        ->with('institution',$institution)
        ->with('constancies',$constancies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $institution = Auth::guard('web_institution')->user();
        $types = Constancy_type::orderBy('id', 'asc')->pluck('name', 'id');

        return view('institution.partials.constancy.constancies.create')
        ->with('types',$types)
        ->with('institution',$institution);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $constancy = Constancy::where([
            ['type_id', '=', $request->type_id],
            ['institution_id', '=',$request->institution_id]
        ])->first();

        if($constancy == null)
        {
            Constancy::create($request->all());

            return redirect()->route('constancy.index');
        }
        else
        {
            return redirect()->route('constancy.edit', $constancy->id);
        }
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
        $constancy = Constancy::findOrFail($id);
        $types = Constancy_type::orderBy('id', 'asc')->pluck('name', 'id');

        return View('institution.partials.constancy.constancies.edit')
        ->with('types',$types)
        ->with('constancy',$constancy);
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
        $constancy = Constancy::findOrFail($id);
        $constancy->fill($request->all());
        $constancy->save();

        // dd($constancy);
        return redirect()->route('constancy.index');
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
