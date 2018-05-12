<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Http\Requests\CreateTeacherRequest;

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

class TeacherController extends ApiController
{

    /***
        METODOS API
    ***/

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
                   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByInstitution(Institution $institution, $year)
    {   
        $year = Schoolyear::where('year','=', ($year == null) ? date('Y') : $year )->first();
        $teachers = $institution->teachers()
        ->whereHas('manager')
        ->with('manager')
        ->with('schoolyear')
        ->with('manager.identification')
        ->with('manager.identification.identification_type')
        ->with('manager.address')
        ->where('school_year_id','=',$year->id)
        ->get();
        
        // dd($teachers);

        return $this->showAll($teachers);
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        $manager = $teacher->with('manager')->first();

        return $this->showOne($manager);
    }

    /***
        METODOS WEB
    ***/

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listTeacher()
    {   
        $institution = Auth::guard('web_institution')->user();

        return view('institution.partials.manager.teacher.index')
                ->with('institution',$institution);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $institution = Auth::guard('web_institution')->user();

        $identifications = Identification_type::orderBy('id', 'ASC')->pluck('name', 'id');
        $cities = City::orderBy('name', 'ASC')->pluck('name', 'id');
        $genders = Gender::orderBy('gender', 'ASC')->pluck('gender', 'id');
        $zones = Zone::orderBy('name', 'ASC')->pluck('name', 'id');

        return View('institution.partials.manager.teacher.create')
                ->with('identification_types', $identifications)
                ->with('cities', $cities)
                ->with('genders', $genders)
                ->with('zones', $zones)
                ->with('institution',$institution);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTeacherRequest $request)
    {

        $identification = Identification::where('identification_number', '=', $request->identification_number)->first();

        // dd($identification->someRelationship());

        $manager = new Manager();
        $address = new Address();

        if(is_null($identification))
        {
            $identification = new Identification();
            $identification->fill($request->all());
            $identification->save();
        }
        else if($identification->someRelationship())
        {
            // dd($identification->someRelationship());
            flash("Este número de identificación esta siendo utilizado por otra persona")->error();

            return redirect()->back()->withInput();
        }

        $address->fill($request->all());
        $address->save();

        $manager->fill($request->all());
        $manager->username = $request->identification_number;
        $manager->password = bcrypt($request->identification_number);
        $manager->state_manager_id = 1;
        $manager->identification_id = $identification->id;
        $manager->address_id = $address->id;
        
        if($manager->save())
        {
           $teacher = new Teacher(); 
           $teacher->code = $request->institution_id."1".$manager->id;
           $teacher->manager_id = $manager->id;
           $teacher->institution_id = $request->institution_id;
           $teacher->school_year_id = 1;
           $teacher->save();
        }
        else
        {
            $identification->delete();
            $address->delete();
        }

        return redirect()->route('institution.list.teacher');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        
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
