<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Carbon\Carbon;
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

        $identification = Identification::where('identification_number', '=', $request->identification_number)->first();

        $student = new Student();
        $address = new Address();

        if(is_null($identification))
        {
            $identification = new Identification();
            $identification->fill($request->all());
            $identification->save();
        
        }else if($identification->someRelationship())
        {
            // dd($identification->someRelationship());
            flash("Este número de identificación esta siendo utilizado por otra persona")->error();

            return redirect()->back()->withInput();
        }

        $address->fill($request->all());
        $address->save();

        $student->fill($request->all());
        $student->identification_id = $identification->id;
        $student->address_id = $address->id;
        $student->username = $request->identification_number;
        $student->password = bcrypt($request->identification_number);
        $student->state_id = 1;

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

            $address = new Address($request->all());
            $family = new Family($request->all());
            $identification = new Identification($request->all());

            // STUDENT
            $student = Student::findOrFail($request->student_id);
            
            $identification->save();
            $address->save();

            $family->identification_id = $identification->id;
            $family->address_id = $address->id;
            $family->save();

            $student->family()->attach($family->id, ['relationship_id'=> $request->relationship_id, 'created_at'=> Carbon::now()]);

            return response()->json([
                'state'     =>  true,
                'message'   => 'Registro agregado con exito',
                'family'    =>  $family,
                'families'  =>  $student->family,
            ]);

        endif;
    }

    /**
    */
    public function attachFamily(Request $request)
    {
        if($request->ajax())
        {
            $student = Student::findOrFail($request->student_id);
            $student->family()->attach($request->family_id, ['relationship_id'=> $request->relationship_id, 'created_at'=> Carbon::now()]);

            return response()->json($student->family);

        }
    }

    public function dettachFamily(Request $request)
    {
        if($request->ajax())
        {

            $student = Student::findOrFail($request->student_id);
            $student->family()->detach($request->family_id);

            return response()->json([
                'state'=>true,
                'family'=>$student->family
            ]);
        }
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
        $family = Family::select('family.*', 'relationship.type', 'address.phone', 'address.mobil', 'address.address', 'address.email', 'identification.identification_number')
                    ->join('family_relationship_student', 'family.id','=','family_relationship_student.family_id')
                     ->join('relationship', 'family_relationship_student.relationship_id', '=', 'relationship.id')
                     ->join('address', 'family.address_id', '=', 'address.id')
                     ->join('identification', 'family.identification_id', '=', 'identification.id')
                     ->where('family_relationship_student.student_id','=', $id)
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


        return response()->json($family);
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

    public function searchFamily(Request $request)
    {

        if($request->ajax())
        {
            return response()->json($request->all());
        }
    }

    public function uploadPicture(Request $request)
    {
        dd($request->all());
    }
}
