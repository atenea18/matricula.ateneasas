<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// MODELS
use App\Student;
use App\Identification;
use App\Address;
use App\AcademicCharacter;
use App\AcademicSpecialty;
use App\Eps;
use App\BloodType;
use App\VictimOfConflict;
use App\Stratum;
use App\Capacity;
use App\Discapacity;

class InscriptionController extends Controller
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
        // return view('institution.partials.inscription.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  
     * @return \Illuminate\Http\Response
     */
    public function createById($id)
    {   
        $student = Student::findOrFail($id);
        $student->identification;
        $student->identification->identification_type;
        $student->identification->city_expedition;
        $student->identification->city_birth;
        $student->identification->gender;

        $student->address;
        $student->address->city;
        $student->address->zone;

        // MEDICAL INFORMATION
        $characters = AcademicCharacter::orderBy('name', 'ASC')->pluck('name', 'id');
        $specialties = AcademicSpecialty::orderBy('name', 'ASC')->pluck('name', 'id');

        // MEDICAL INFORMATION
        $eps = Eps::orderBy('name', 'ASC')->pluck('name', 'id');
        $blood_types = BloodType::orderBy('id', 'ASC')->pluck('blood_type', 'id');

        // VICTIM OF CONFLICT
        $victims = VictimOfConflict::orderBy('name', 'ASC')->pluck('name', 'id');

        // SOCIOECONOMIC
        $stratums = Stratum::orderBy('stratum', 'ASC')->pluck('stratum', 'id');

        // CAPACITY AND DISCAPACITY
        $capacities = Capacity::orderBy('name', 'ASC')->pluck('name', 'id');
        $discapacities = Discapacity::orderBy('name', 'ASC')->pluck('name', 'id');

        // dd($student);
        
        return  view('institution.partials.inscription.create')
                ->with('student', $student)
                ->with('characters', $characters)
                ->with('specialties', $specialties)
                ->with('eps', $eps)
                ->with('blood_types', $blood_types)
                ->with('victims', $victims)
                ->with('stratums', $stratums)
                ->with('capacities', $capacities)
                ->with('discapacities', $discapacities);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
