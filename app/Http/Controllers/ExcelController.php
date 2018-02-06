<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Institution;
use App\Headquarter;
use App\Identification;
use App\Identification_type;
use App\Address;
use App\Student;
use App\Group;
use App\AcademicInformation;
use App\MedicalInformation;
use App\Displacement;
use App\SocioeconomicInformation;
use App\Territorialty;
use App\Family;
use App\Enrollment;
use App\Gender;
use App\StudentState;
use App\BloodType;

class ExcelController extends Controller
{
    //

    public function exportInstitutions()
    {
    	$institutions = Institution::all();

    	// dd($institutions);
    	\Excel::create('Instituciones', function($excel) {

    		$institutions = Institution::all();

    		$excel->sheet('institutions', function($sheet) use($institutions) {

    			$sheet->row(1, [
    				'Id', 'Nombre', 'Cod_dane', 'Correo', 'Contrasena', 'fecha_creacion', 'fecha_actualizacion'
    			]);

    			foreach($institutions as $index => $institution):
    				$sheet->row($index+2, [
    					$institution->id, $institution->name, $institution->dane_code, $institution->email, $institution->password, $institution->created_at, $institution->updated_at
    				]);
    			endforeach;
    		});

    	})->export('xlsx');	
    }


    // IMPORTACIONES


    public function importIdentification(Request $request)
    {
    	\Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                $iden_pre = Identification::where('identification_number','=',$row->identification_number)->first();

                if($iden_pre != null):
                    $identification = new Identification();
                    $identification->id = $row->id;
                    $identification->identification_number = $row->identification_number;
                    $identification->birthdate = $row->birthdate;
                    $identification->identification_type_id = $row->identification_type_id;
                    $identification->id_city_expedition = $row->id_city_expedition;
                    $identification->gender_id = $row->gender_id;
                    $identification->id_city_of_birth = $row->id_city_of_birth;
                    $identification->created_at = $row->created_at;
                    $identification->updated_at = $row->updated_at;
                    $identification->save();
                endif;
            });
        
        });
    }

    public function importAddress(Request $request)
    {
        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                // dd($row);
                $address = new Address();
                $address->id = $row->id;
                $address->address = $row->address;
                $address->neighborhood = $row->neighborhood;
                $address->phone = $row->phone;
                $address->mobil = $row->mobil;
                $address->email = $row->email;
                $address->id_city_address = $row->id_city_address;
                $address->zone_id = $row->zone_id;
                $address->created_at = $row->created_at;
                $address->updated_at = $row->updated_at;
                $address->save();
            });
        
        });
    }

    public function importStudents(Request $request)
    {
        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                // dd($row);
                $student = new Student();
                $student->id = $row->id;
                $student->name = $row->name;
                $student->last_name = $row->last_name;
                $student->username = $row->username;
                $student->password = $row->password;
                $student->state_id = 1;
                $student->identification_id = $row->identification_id;   
                $student->address_id = $row->address_id;
                $student->created_at = $row->created_at;
                $student->updated_at = $row->updated_at;
                // dd($student);

                if($student->name != null && $student->last_name != null)
                    $student->save();
            });
        
        });
    }


    public function importInstitutions(Request $request)
    {
        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                $institution = new Institution();
                $institution->id = $row->id;
                $institution->name = $row->name;
                $institution->dane_code = $row->dane_code;
                $institution->email = $row->email;
                $institution->password = $row->password;
                $institution->created_at = $row->created_at;
                $institution->updated_at = $row->updated_at;

                if($institution->name != null)
                    $institution->save();
            });
        
        });
    }


    public function importHeadquarters(Request $request)
    {
        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                $headquarter = new Headquarter();
                $headquarter->id = $row->id;
                $headquarter->name = $row->name;
                $headquarter->nit = $row->nit;
                $headquarter->institution_id = $row->institution_id;
                $headquarter->address_id = $row->address_id;
                $headquarter->created_at = $row->created_at;
                $headquarter->updated_at = $row->updated_at;

                if($headquarter->name != null)
                    $headquarter->save();
            });
        
        });
    }

    public function importGroups(Request $request)
    {
        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                $group = new Group();
                $group->id = $row->id;
                $group->name = $row->name;
                $group->quota = $row->quota;
                $group->headquarter_id = $row->headquarter_id;
                $group->grade_id = $row->grade_id;
                $group->working_day_id = $row->working_day_id;
                $group->created_at = $row->created_at;
                $group->updated_at = $row->updated_at;

                if($group->name != null)
                    $group->save();
            });
        
        });
    }

    public function importAcademicInformation(Request $request)
    {
        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                $ai = new AcademicInformation();
                $ai->id = $row->id;
                $ai->has_subsidy = $row->has_subsidy;
                $ai->student_id = $row->student_id;
                $ai->academic_character_id = $row->academic_character_id;
                $ai->academic_specialty_id = $row->academic_specialty_id;
                $ai->created_at = $row->created_at;
                $ai->updated_at = $row->updated_at;

                if($ai->student_id != null)
                    $ai->save();
            });
        
        });
    }

    public function importMedicalInformation(Request $request){

        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                $student = Student::find($row->student_id);

                $mi = new MedicalInformation();
                $mi->id = $row->id;
                $mi->ips = $row->ips;
                $mi->ars = $row->ars;
                $mi->student_id = $row->student_id;
                $mi->eps_id = $row->eps_id;
                $mi->blood_type_id = $row->blood_type_id;
                $mi->created_at = $row->created_at;
                $mi->updated_at = $row->updated_at;

                if($student != null)
                    $mi->save();
            });
        
        });
    }

    public function importDisplacements(Request $request){

        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                $student = Student::find($row->student_id);

                $di = new Displacement();
                $di->id = $row->id;
                $di->expulsion_date = $row->expulsion_date;
                $di->certificate = $row->certificate;
                $di->student_id = $row->student_id;
                $di->victim_of_conflict_id = $row->victim_of_conflict_id;
                $di->created_at = $row->created_at;
                $di->updated_at = $row->updated_at;

                if($student != null)
                    $di->save();
            });
        
        });
    }

    public function importSocioEconomic(Request $request){

        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                $student = Student::find($row->student_id);

                $soc = new SocioeconomicInformation();
                $soc->id = $row->id;
                $soc->sisben_number = $row->sisben_number;
                $soc->sisben_level = $row->sisben_level;
                $soc->amcf = $row->amcf;
                $soc->bhdmcf = $row->bhdmcf;
                $soc->bvfp = $row->bvfp;
                $soc->bhn = $row->bhn;
                $soc->student_id = $row->student_id;
                $soc->stratum_id = $row->stratum_id;
                $soc->created_at = $row->created_at;
                $soc->updated_at = $row->updated_at;

                if($student != null)
                    $soc->save();
            });
        
        });
    }

    public function importTeritorrialty(Request $request){

        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                $student = Student::find($row->student_id);

                $ter = new Territorialty();
                $ter->id = $row->id;
                $ter->guard = $row->guard;
                $ter->ethnicity = $row->ethnicity;
                $ter->student_id = $row->student_id;
                $ter->created_at = $row->created_at;
                $ter->updated_at = $row->updated_at;

                if($student != null)
                    $ter->save();
            });
        
        });
    }

    public function importFamily(Request $request){

        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                $student = Student::find($row->student_id);

                $fam = new Family();
                $fam->id = $row->id;
                $fam->name = $row->name;
                $fam->last_name = $row->last_name;
                $fam->identification_id = $row->identification_id;
                $fam->address_id = $row->address_id;
                $fam->created_at = $row->created_at;
                $fam->updated_at = $row->updated_at;

                if($student != null)
                    $fam->save();
            });
        
        });
    }

    public function importFamilyRelation(Request $request){

        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                $student = Student::find($row->student_id);
                $family = Family::find($row->family_id);
                
                if($student != null && $family != null)
                    $student->family()->attach($row->family_id, ['relationship_id'=> $row->relationship_id, 'created_at'=> $row->created_at]);
            });
        
        });
    }

    public function importEnrollment(Request $request){

        \Excel::load($request->excel, function($reader) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) {

                $student = Student::find($row->student_id);
                $headquarter = Headquarter::find($row->headquarter_id);
                $group = Group::find($row->group_id);
                $enrollment = new Enrollment();

                $institution = $headquarter->institution;

                if($headquarter != null && $student != null){
                    $enrollment->id = $row->id;
                    $enrollment->code =  "2018".time()."-".$student->id;
                    // $enrollment->folio =
                    $enrollment->school_year_id = 1;
                    $enrollment->student_id = $row->student_id;
                    $enrollment->garde_id = ($group != null) ? $group->grade->id : null ;
                    $enrollment->enrollment_state_id = ($group != null) ? 3 : 2 ;
                    $enrollment->institution_id = $institution->id;
                    $enrollment->created_at = $row->created_at;
                    $enrollment->updated_at = $row->updated_at;

                    if($enrollment->save() && $group != null){
                        $enrollment->attachGroupEnrollment($group->id);
                    }

                    // dd($enrollment);

                }
            });
        
        });
    }

    public function oldStudent(Request $request)
    {

        // dd($request->all());
        \Excel::load($request->excel, function($reader) use($request) {
 
            $excel = $reader->get();
     
            // iteracción
            $reader->each(function($row) use($request){

                $identification = Identification::where('identification_number','=',$row->numero_documento)->first();
                $identification_type =Identification_type::where('abbreviation', '=', $row->tipo_identificacion)->first();
                $gender = Gender::where('prefix', '=', $row->genero)->first();
                $bloodType = BloodType::where('blood_type', '=', $row->tipo_sangre)->first();

                if($identification == null):
                    $identification = new Identification();
                    $identification->identification_number = $row->numero_documento;
                    $identification->birthdate = $row->fecha_nacimiento;
                    $identification->identification_type_id = $identification_type->id;
                    $identification->gender_id = $gender->id;
                    $identification->save();

                    $address = new Address();
                    $address->address = $row->address;
                    $address->neighborhood = $row->barrio;
                    $address->phone = $row->telefono;
                    $address->mobil = $row->telefono;
                    $address->zone_id = 2;
                    $address->save();

                    $student = new Student();
                    $student->name = $row->primer_nombre." ".$row->segundo_nombre;
                    $student->last_name = $row->primer_apellido." ".$row->segundo_apellido;
                    $student->username = $row->numero_documento;
                    $student->password = $row->numero_documento;
                    $student->state_id = 2;
                    $student->identification_id = $identification->id;   
                    $student->address_id = $address->id;
                    $student->save();

                    $ai = new AcademicInformation();
                    $ai->has_subsidy = 0;
                    $ai->student_id = $student->id;
                    $ai->academic_character_id = 3;
                    $ai->academic_specialty_id = 6;
                    $ai->save();

                    $mi = new MedicalInformation();
                    $mi->ips = $row->ips;
                    $mi->student_id = $student->id;
                    $mi->eps_id = ($row->eps == 0) ? 49 : $row->eps_id;
                    $mi->blood_type_id = ($bloodType == null) ? null : $bloodType->id;
                    $mi->save();

                    $di = new Displacement();
                    $di->student_id = $student->id;
                    $di->victim_of_conflict_id = 5;
                    $di->save();

                    $soc = new SocioeconomicInformation();
                    $soc->sisben_number = $row->numero_carne_sisben;
                    $soc->sisben_level = $row->nivel_sisben;
                    $soc->student_id = $student->id;
                    $soc->stratum_id = $row->estrato;
                    $soc->save();

                    $ter = new Territorialty();
                    $ter->guard = $row->resguardo;
                    $ter->ethnicity = $row->etnia;
                    $ter->student_id = $student->id;
                    $ter->save();

                    $enrollment = new Enrollment();
                    $enrollment->code =  "2017".time()."-".$student->id;
                    $enrollment->school_year_id = 2;
                    $enrollment->student_id = $student->id;
                    $enrollment->garde_id = ($row->grado_igreso == null) ? null : $row->grado_igreso;
                    $enrollment->enrollment_state_id = 3;
                    $enrollment->institution_id = 6;
                    $enrollment->save();

                endif;
            });
            
            echo "Terminado.....";
        });
    }
}
