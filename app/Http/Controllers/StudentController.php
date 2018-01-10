<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// MODELS
use App\Student;
use App\Identification_type;
use App\Identification;
use App\Address;
use App\City;
use App\Zone;
use App\Gender;
use App\Family;

// REQUESTS
use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\CreateFamilyRequest;
use App\Http\Requests\UpdateFamilyRequest;

class StudentController extends Controller
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
        $identifications = Identification_type::orderBy('id', 'ASC')->pluck('name', 'id');
        $cities = City::orderBy('name', 'ASC')->pluck('name', 'id');
        $genders = Gender::orderBy('gender', 'ASC')->pluck('gender', 'id');
        $zones = Zone::orderBy('name', 'ASC')->pluck('name', 'id');

        // dd($identifications);

        return  view('institution.partials.student.create')
                ->with('identification_types', $identifications)
                ->with('cities', $cities)
                ->with('genders', $genders)
                ->with('zones', $zones);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStudentRequest $request)
    {
        $student = new Student();
        $address = new Address();
        $identification = new Identification();

        $identification->fill($request->all());
        $address->fill($request->all());
        $student->fill($request->all());

        $identification->save();
        $address->save();

        $student->identification_id = $identification->id;
        $student->address_id = $address->id;
        $student->username = $request->identification_number;
        $student->password = bcrypt($request->identification_number);

        $student->save();

        return redirect()->route('enrollment.create', ['id'=>$student->id]);
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

    /**
     *
     *
     */
    public function addFamily(CreateFamilyRequest $request)
    {

        if($request->ajax()):

            $address = new Address();
            $family = new Family();
            $identification = new Identification();

            // STUDENT
            $student = Student::findOrFail($request->student_id);

            $address->fill($request->all());
            $identification->fill($request->all());
            
            $identification->save();
            $address->save();

            $family->fill($request->all());
            $family->student_id = $student->id;
            $family->identification_id = $identification->id;
            $family->address_id = $address->id;
            $family->save();

            $student->family()->attach($family->id, ['relationship_id'=> $request->relationship_id]);

            return response()->json([
                'state'     =>  true,
                'message'   => 'Registro agregado con exito',
                'family'    =>  $family,
                'families'  =>  $student->family,
            ]);

        endif;
    }


    /**
     *
     *
     */
    public function updateFamily(UpdateFamilyRequest $request, $id)
    {

        if($request->ajax()):

            // STUDENT
            $student = Student::findOrFail($request->student_id);

            $family = Family::findOrFail($id);
            $address = Address::findOrFail($family->address_id);
            $identification = Identification::findOrFail($family->identification_id);

            $family->fill($request->all());
            $address->fill($request->all());
            $identification->fill($request->all());


            $family->save();
            $address->save();
            $identification->save();

            $student->family()->updateExistingPivot($family->id, ['relationship_id'=> $request->relationship_id]);

            return response()->json([
                'state'     =>  true,
                'family' => $student->family,
                'address' => $address,
                'identification'=> $identification,
            ]);

        endif;
    }

    /**
     *
     *
     */
    public function getFamily(Request $request, $id)
    {
        $family = Family::join('family_relationship_student', 'family.id','=','family_relationship_student.family_id')
                     ->join('relationship', 'family_relationship_student.relationship_id', '=', 'relationship.id')
                     ->join('address', 'family.address_id', '=', 'address.id')
                     ->join('identification', 'family.identification_id', '=', 'identification.id')
                     ->select('family.*', 'relationship.type', 'address.phone', 'address.mobil', 'address.address', 'address.email', 'identification.identification_number')
                     ->where('family.student_id','=', $id)
                     ->get();

        return response()->json([
            'data'  =>  $family,
        ]);
    }

    /**
     *
     *
     */
    public function getFamilyById($id){

        $family = Family::findOrFail($id);
        $family->address;
        $family->identification;
        $family->students;


        return response()->json([
            $family
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFamily(Request $request, $id)
    {
        // STUDENT
        $student = Student::findOrFail($request->student_id);

        $family = Family::findOrFail($id);
        $address = Address::findOrFail($family->address_id);
        $identification = Identification::findOrFail($family->identification_id);

        $family->delete();
        $address->delete();
        $identification->delete();

        $student->family()->detach($request->relationship_id);

        return response()->json([
            'state'     =>  true,
            'family' => $student->family,
            'address' => $address,
            'identification'=> $identification,
        ]);
    }
}
