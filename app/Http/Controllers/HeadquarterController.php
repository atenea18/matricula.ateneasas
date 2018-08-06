<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateHeadquarterRequest;

use Auth;

// MODELS
use App\Address;
use App\City;
use App\Zone;
use App\Headquarter;

class HeadquarterController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $institution_id = Auth::guard('web_institution')->user()->id;

        $headquarters = Headquarter::getByInstitution($institution_id);

        $headquarters->each(function($headquarters){
            $headquarters->address;
        });

        // dd($headquarters);

        return view('institution.partials.headquarter.index')
                ->with('headquarters', $headquarters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   

        $cities = City::orderBy('name', 'ASC')->pluck('name', 'id');
        $zones = Zone::orderBy('name', 'ASC')->pluck('name', 'id');

        return  view('institution.partials.headquarter.create')
                ->with('cities', $cities)
                ->with('zones', $zones);
    }

	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateHeadquarterRequest $request)
    {

        $address = new Address();
        $headquarter = new Headquarter();

        $address->fill($request->all());
        $headquarter->fill($request->all());

        $address->save();

        $headquarter->address_id = $address->id;
        $headquarter->institution_id = Auth::guard('web_institution')->user()->id;
        $headquarter->save();

        return redirect()->route('headquarter.index');

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
        $headquarter = Headquarter::findOrFail($id);

        $cities = City::orderBy('name', 'ASC')->pluck('name', 'id');
        $zones = Zone::orderBy('name', 'ASC')->pluck('name', 'id');

        return  view('institution.partials.headquarter.edit')
                ->with('cities', $cities)
                ->with('zones', $zones)
                ->with('headquarter', $headquarter);
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
        $headquarter = Headquarter::findOrFail($id);
        $address = Address::findOrFail($headquarter->address_id);

        $headquarter->fill($request->all());
        $address->fill($request->all());

        $headquarter->save();
        $address->save();

        return redirect()->route('headquarter.index');
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
