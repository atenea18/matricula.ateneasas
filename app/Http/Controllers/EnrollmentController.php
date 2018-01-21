<?php

namespace App\Http\Controllers;

use App\Grade;
use App\Group;
use App\Helpers\GenerateEnrollmentCard;
use Illuminate\Http\Request;
use App\Http\Requests\CreateStudentEnrollmentRequest;

use Auth;

// MODELS
use App\City;
use App\Identification_type;
use App\Zone;
use App\Gender;
use App\RelationshipFamily;

use App\Student;
use App\Identification;
use App\Address;
use App\AcademicCharacter;
use App\AcademicSpecialty;
use App\Institution;
use App\Headquarter;
use App\Workingday;
use App\Eps;
use App\BloodType;
use App\VictimOfConflict;
use App\Stratum;
use App\Capacity;
use App\Discapacity;
use App\AcademicInformation;
use App\MedicalInformation;
use App\Displacement;
use App\SocioeconomicInformation;
use App\Territorialty;
use App\Enrollment;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($state = 1)
    {

        return view('institution.partials.enrollment.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int
     * @return \Illuminate\Http\Response
     */
    public function createById($id)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;

        $student = Student::findOrFail($id);
        $student->identification;
        $student->identification->identification_type;
        $student->identification->city_expedition;
        $student->identification->city_birth;
        $student->identification->gender;

        $student->address;
        $student->address->city;
        $student->address->zone;
        // $student->relationshipFamily;

        // ACADEMIC INFORMATION
        $characters = AcademicCharacter::orderBy('name', 'ASC')->pluck('name', 'id');
        $specialties = AcademicSpecialty::orderBy('name', 'ASC')->pluck('name', 'id');
        $headquarters = Headquarter::where('institution_id', '=', $institution_id)->orderBy('name', 'ASC')->pluck('name', 'id');
        $journeys = Workingday::orderBy('id', 'ASC')->pluck('name', 'id');

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


        $identifications = Identification_type::orderBy('id', 'ASC')->pluck('name', 'id');
        $cities = City::orderBy('name', 'ASC')->pluck('name', 'id');
        $genders = Gender::orderBy('gender', 'ASC')->pluck('gender', 'id');
        $zones = Zone::orderBy('name', 'ASC')->pluck('name', 'id');
        $relationship_types = RelationShipFamily::orderBy('type', 'ASC')->pluck('type', 'id');

        // dd($student);

        return view('institution.partials.enrollment.create')
            ->with('student', $student)
            ->with('characters', $characters)
            ->with('specialties', $specialties)
            ->with('eps', $eps)
            ->with('blood_types', $blood_types)
            ->with('victims', $victims)
            ->with('stratums', $stratums)
            ->with('capacities', $capacities)
            ->with('discapacities', $discapacities)
            ->with('identification_types', $identifications)
            ->with('cities', $cities)
            ->with('genders', $genders)
            ->with('zones', $zones)
            ->with('relationship_types', $relationship_types)
            ->with('headquarters', $headquarters)
            ->with('journeys', $journeys);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStudentEnrollmentRequest $request)
    {


        // STUDENT
        $student = Student::findOrFail($request->student_id);

        // dd($enrollment);
        
        // ACADEMIC INFORMATION
        $enrollment = new Enrollment($request->all());
        $academic_information = new AcademicInformation($request->all());
        $academic_information->save();
        $enrollment->save();

        // MEDICAL INFORMATION
        $medical_information = new MedicalInformation($request->all());
        $medical_information->save();


        // DISPLACEMENT
        $displacement = new Displacement($request->all());
        $displacement->save();


        // SOCIOECONOMIC INFORMATION
        $socioeconomic = new SocioEconomicInformation($request->all());
        $socioeconomic->save();


        // TERRITORIALTY
        $territorialty = new Territorialty($request->all());
        $territorialty->save();

        // CAPACITIES AND DISCAPACITIES
        $student->capacities()->sync($request->capacity_id);
        $student->discapacities()->sync($request->discapacity_id);

        return redirect()->route('enrollment.lists', 1);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        // $enrollment->headquarter;
        // $enrollment->student->academicInformation;
        // $enrollment->student->medicalInformation;
        // $enrollment->student->displacement;
        // $enrollment->student->socioeconomicInformation;
        // $enrollment->student->territorialty;
        // $enrollment->student->capacities;
        // $enrollment->student->discapacities;

        $institution_id = Auth::guard('web_institution')->user()->id;

        // dd($enrollment);
        // ACADEMIC INFORMATION
        $groups = array();
        $characters = AcademicCharacter::orderBy('name', 'ASC')->pluck('name', 'id');
        $specialties = AcademicSpecialty::orderBy('name', 'ASC')->pluck('name', 'id');
        $headquarters = Headquarter::where('institution_id', '=', $institution_id)->orderBy('name', 'ASC')->pluck('name', 'id');
        $journeys = Workingday::orderBy('id', 'ASC')->pluck('name', 'id');

        if($enrollment->group != null)
            $groups = Group::where([
                ['working_day_id', '=', $enrollment->group->workingday->id],
                ['headquarter_id','=',$enrollment->headquarter_id]
            ])->orderBy('grade_id','ASC')->pluck('name', 'id');

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


        $identifications = Identification_type::orderBy('id', 'ASC')->pluck('name', 'id');
        $cities = City::orderBy('name', 'ASC')->pluck('name', 'id');
        $genders = Gender::orderBy('gender', 'ASC')->pluck('gender', 'id');
        $zones = Zone::orderBy('name', 'ASC')->pluck('name', 'id');
        $relationship_types = RelationShipFamily::orderBy('type', 'ASC')->pluck('type', 'id');

        // dd($enrollment);
        return view('institution.partials.enrollment.edit')
            ->with('enrollment', $enrollment)
            ->with('characters', $characters)
            ->with('specialties', $specialties)
            ->with('groups',$groups)
            ->with('eps', $eps)
            ->with('blood_types', $blood_types)
            ->with('victims', $victims)
            ->with('stratums', $stratums)
            ->with('capacities', $capacities)
            ->with('discapacities', $discapacities)
            ->with('identification_types', $identifications)
            ->with('cities', $cities)
            ->with('genders', $genders)
            ->with('zones', $zones)
            ->with('relationship_types', $relationship_types)
            ->with('headquarters', $headquarters)
            ->with('journeys', $journeys)
            ->with('student', $enrollment->student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // STUDEN
        $student = Student::findOrFail($request->student_id);

        $student->fill($request->all());
        $student->update();

        // ACADEMIC INFORMATION
        $academic_information = AcademicInformation::findOrFail($request->academic_information_id);
        $academic_information->fill($request->all());
        $academic_information->save();

        // ENROLLMENT
        $enrollment = Enrollment::findOrFail($request->enrollment_id);
        $enrollment->fill($request->all());
        $enrollment->save();


        // MEDICAL INFORMATION
        $medical_information = MedicalInformation::findOrFail($request->medical_information_id);
        $medical_information->fill($request->all());
        $medical_information->save();

        // DISPLACEMENT
        $displacement = Displacement::findOrFail($request->displacement_id);
        $displacement->fill($request->all());
        $displacement->save();


        // SOCIOECONOMIC INFORMARTION
        $socioeconomic = SocioeconomicInformation::findOrFail($request->socioeconomic_information_id);
        $socioeconomic->fill($request->all());
        // $socioeconomic->save();
        $socioeconomic->amcf = ($request->has('amcf')) ? 1 : 0;
        $socioeconomic->bhdmcf = ($request->has('bhdmcf')) ? 1 : 0;
        $socioeconomic->bvfp = ($request->has('bvfp')) ? 1 : 0;
        $socioeconomic->bhn = ($request->has('bhn')) ? 1 : 0;

        // dd($request->all());

        // TERRITORIALTY
        $territorialty = Territorialty::findOrFail($request->territorialty_id);
        $territorialty->fill($request->all());
        $territorialty->save();

        // CAPACITIES AND DISCAPACITIES
        $student->capacities()->sync($request->capacity_id);
        $student->discapacities()->sync($request->discapacity_id);

        // dd($request->all());
        return redirect()->route('enrollment.lists', 1);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * List all Enrollment
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function lists($state)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;

        // dd($institution_id);

        $enrollments = Enrollment::getByState($state, $institution_id);

        $enrollments->each(function ($enrollments) {
            $enrollments->student->identification->identification_type;
            $enrollments->headquarter;
        });

        // dd($enrollments);

        return view('institution.partials.enrollment.index')
            ->with('enrollments', $enrollments);
    }

    public function cardGrade()
    {

        $grades = Grade::all();

        $institution_id = Auth::guard('web_institution')->user()->id;
        return view('institution.partials.enrollment.card',
            compact('grades'));
    }

    public function cardGroup()
    {
        $institution_id = Auth::guard('web_institution')->user()->id;

        $groups = Group::getAllByInstitution($institution_id);

        $institution_id = Auth::guard('web_institution')->user()->id;
        return view('institution.partials.enrollment.card',
            compact('groups'));
    }

    public function cardStudent()
    {
        $student = true;


        $institution_id = Auth::guard('web_institution')->user()->id;
        return view('institution.partials.enrollment.card',
            compact('student'));
    }

    public function generateCard(Request $request)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        $typecard = $request->typecard;
        $students_enrollment_card = [];

        switch ($typecard){
            case 'byGrade':
                $grade_id = $request->grade_id;
                $students_enrollment_card = Enrollment::getEnrollmentCardGrade($grade_id, $institution_id);
                break;
            case 'byGroup':
                $group_id = $request->group_id;
                $students_enrollment_card = Enrollment::getEnrollmentCardGroup($group_id, $institution_id);
                break;
            case 'byStudent':
                break;
        }



        $this->printCard($students_enrollment_card, $institution_id);
    }

    private function printCard($students, $institution_id){

        $students_enrollment_card = $students;
        $print = new GenerateEnrollmentCard();
        $print->generateEnrollmentCard($students_enrollment_card, $institution_id);
        $print->Output('D', 'fichaMatricula.pdf');
    }

    public function enrollmentAutocomplete(Request $request)
    {
        $term = $request->text;
        $data = Student::where('name', 'LIKE', '%' . $term . '%')
            ->take(10)
            ->get();
        $result = array();
        foreach ($data as $key => $value) {
            $result[] = ['value' => $value->name];
        }
        return response()->json($result);
    }


}
