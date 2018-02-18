<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Identification_type;
use App\Identification;
use App\Institution;
use App\Schoolyear;
use App\Address;
use App\Manager;
use App\Teacher;
use App\Gender;
use App\City;
use App\Zone;

use Auth;

class ManagerController extends Controller
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
    public function edit(Manager $manager)
    {
        $identifications = Identification_type::orderBy('id', 'ASC')->pluck('name', 'id');
        $cities = City::orderBy('name', 'ASC')->pluck('name', 'id');
        $genders = Gender::orderBy('gender', 'ASC')->pluck('gender', 'id');
        $zones = Zone::orderBy('name', 'ASC')->pluck('name', 'id');

        // dd($manager);
        return View('institution.partials.manager.teacher.edit')
                ->with('identification_types', $identifications)
                ->with('cities', $cities)
                ->with('genders', $genders)
                ->with('zones', $zones)
                ->with('manager',$manager);
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

        // dd($request->all());
        $manager = Manager::findOrFail($id);
        $address = Address::findOrFail($manager->address_id);
        $identification = Identification::findOrFail($manager->identification_id);

        $identification->fill($request->all());
        $address->fill($request->all());
        
        $identification->save();
        $address->save();

        $manager->fill($request->all());
        $manager->save();

        return redirect()->route('institution.list.teacher');
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
