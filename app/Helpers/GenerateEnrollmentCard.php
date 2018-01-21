<?php

namespace App\Helpers;

/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 12/01/2018
 * Time: 8:57 PM
 */

use App\AcademicCharacter;
use App\AcademicInformation;
use App\Grade;
use App\Group;
use App\Headquarter;
use App\Institution;
use App\Student;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use setasign\Fpdi\Fpdi;


class GenerateEnrollmentCard extends Fpdi
{
    public function myHeader($institution)
    {
        $this->setSourceFile('template/card.pdf');
        $pageId = $this->importPage(1);
        $this->useTemplate($pageId, null, null, null, null, true);

        $this->SetLineWidth(.1);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(0, 0, 0);

        parent::Header();
        $width_page = $this->GetPageWidth() - 20;
        $this->Cell(180, 10, strtoupper(utf8_decode($institution)), 0, 1, 'C');
        $this->ln(15);
        $this->SetFont('Arial', '', 6);
        $this->setY(35);
    }

    public function generateEnrollmentCard($enrollment_card, $institution_id)
    {
        $institution = Institution::findOrfail($institution_id);

        foreach ($enrollment_card as $enrollment) {
            date_default_timezone_set('UTC');
            $this->AddPage('P', 'Letter');
            $this->myHeader($institution->name);

            $headquarter = Headquarter::findOrfail($enrollment->headquarter_id);

            $lineas = 0;

            # Sesion formato de matricula de estudiante
            $this->Cell(119.5, 8, '', $lineas, 0, 'C');
            if (0 == 0) {
                //Nuevo
                $this->Cell(39.5, 8, "X", $lineas, 0, 'C');
                $this->Cell(39.5, 8, "", $lineas, 1, 'C');
            } else {
                //Continuidad
                $this->Cell(39.5, 8, "", $lineas, 0, 'C');
                $this->Cell(40, 8, '', $lineas, 1, 'C');
            }
            $this->ln(4);
            $grade_enrollment = Grade::findOrfail(Group::findOrfail($enrollment->group->id)->grade_id)->name;

            # Fecha Matricula
            $this->Cell(99.5, 4, '', $lineas, 0, 'C');
            $this->Cell(20, 4, strtoupper(utf8_decode($grade_enrollment)), $lineas, 0, 'C');
            $this->Cell(19.5, 4, "", $lineas, 0, 'C');
            $this->Cell(20, 4, "", $lineas, 0, 'C');
            $this->Cell(19.5, 4, "", $lineas, 0, 'C');
            $this->Cell(20, 4, '', $lineas, 0, 'C');
            $this->ln(12);


            #Datos de la institución
            $this->Cell(79.5, 4, strtoupper(utf8_decode($institution->name)), $lineas, 0, 'C');
            $this->Cell(59.5, 4, strtoupper(utf8_decode($headquarter->name)), $lineas, 0, 'C');
            $this->Cell(59.5, 4, strtoupper(utf8_decode($headquarter->address->city->name)), $lineas, 0, 'C');
            $this->ln(4);
            $this->Cell(19, 4, "", $lineas, 0, 'C');
            $this->Cell(40.7, 4, strtoupper(utf8_decode('')), $lineas, 0, '');
            $this->Cell(19, 4, "", $lineas, 0, 'C');
            $this->Cell(60.5, 4, strtoupper(utf8_decode('')), $lineas, 0, '');
            $this->Cell(19, 4, "", $lineas, 0, 'C');
            $this->Cell(40.7, 4, "", $lineas, 0, 'C');
            $this->ln(16);

            /*
             Datos de identificación del estudiante
            */
            $student = Student::findOrfail($enrollment->student_id);
            $identification = $student->identification;
            $gender = $student->identification->gender;
            $city_birth = $student->identification->city_birth;
            $city_expedition = $student->identification->city_expedition;
            $identification_type = $student->identification->identification_type;
            $province_birth = $student->identification->city_birth->province;
            $province_expedition = $student->identification->city_expedition->province;
            $address = $student->address;
            $zone = $student->address->zone;
            $city_address = $student->address->city;
            $province_address = $student->address->city->province;


            $last_names[0] = '';
            $names[0] = '';
            $last_names = explode(' ', $student->last_name);
            $names = explode(' ', $student->name);

            if (count($last_names) == 1)
                $last_names[1] = '';
            if (count($names) == 1)
                $names[1] = '';

            #Datos de identificación
            $identification_type_id = $identification_type->id;
            $this->Cell(4.7, 3.6, "", $lineas, 0, 'C');
            $this->Cell(4.9, 3.6, ($identification_type_id == 1 ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(5, 3.6, "", $lineas, 0, 'C');
            $this->Cell(5, 3.6, ($identification_type_id == 3 ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(5, 3.6, "", $lineas, 0, 'C');
            $this->Cell(5, 3.6, ($identification_type_id == 2 ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(5, 3.6, "", $lineas, 0, 'C');
            $this->Cell(5, 3.6, ($identification_type_id == 0 ? "X" : ""), $lineas, 0, 'C');

            $this->Cell(39.7, 3.6, $identification->identification_number, $lineas, 0, 'C');
            $this->Cell(19.8, 3.6, Carbon::parse($student->identification->birthdate)->age, $lineas, 0, 'C');
            $this->Cell(29.8, 3.6, strtoupper(utf8_decode($province_expedition->name)), $lineas, 0, 'C');
            $this->Cell(29.8, 3.6, strtoupper(utf8_decode($city_expedition->name)), $lineas, 0, 'C');

            #Marcar con x Masculino o Femenino
            $gender_id = $gender->id;
            $this->Cell(14.9, 3.6, "", $lineas, 0, 'C');
            $this->Cell(5, 3.6, ($gender_id == 1 ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(14.9, 3.6, "", $lineas, 0, 'C');
            $this->Cell(5, 3.6, ($gender_id == 2 ? "X" : ""), $lineas, 0, 'C');
            $this->ln(12);

            $this->Cell(29.8, 3.6, strtoupper(utf8_decode($last_names[0])), $lineas, 0, 'C');
            $this->Cell(29.8, 3.6, strtoupper(utf8_decode($last_names[1])), $lineas, 0, 'C');
            $this->Cell(29.8, 3.6, strtoupper(utf8_decode($names[0])), $lineas, 0, 'C');
            $this->Cell(29.8, 3.6, strtoupper(utf8_decode($names[1])), $lineas, 0, 'C');

            $this->Cell(24.8, 3.6, strtoupper(utf8_decode($province_birth->name)), $lineas, 0, 'C');//Depar nacimiento
            $this->Cell(24.8, 3.6, strtoupper(utf8_decode($city_birth->name)), $lineas, 0, 'C');//Municipio nacimiento

            if (!isset($student->identification->birthdate)) {
                $fecha_nacimiento_est = array(0 => "", 1 => "", 2 => "");;
            } else {
                $fecha_nacimiento_est = explode("-", $student->identification->birthdate);
            }


            #fecha nacimiento
            $this->Cell(9.9, 3.6, $fecha_nacimiento_est[2], $lineas, 0, 'C'); #Día
            $this->Cell(9.9, 3.6, $fecha_nacimiento_est[1], $lineas, 0, 'C'); #Mes
            $this->Cell(9.9, 3.6, $fecha_nacimiento_est[0], $lineas, 0, 'C'); #Año


            $this->ln(12);
            $this->Cell(39.9, 3.6, strtoupper(utf8_decode($address->address)), $lineas, 0, 'C');
            $this->Cell(39.9, 3.6, strtoupper(utf8_decode($address->neighborhood)), $lineas, 0, 'C');

            $tipo_zona = strtoupper($zone->id);
            $this->Cell(9.9, 3.6, ($tipo_zona == 2 ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(9.9, 3.6, ($tipo_zona == 1 ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(29.8, 3.6, strtoupper(utf8_decode($province_address->name)), $lineas, 0, 'C'); #departamento Residencia
            $this->Cell(29.8, 3.6, strtoupper(utf8_decode($city_address->name)), $lineas, 0, 'C'); #Municipio Residencia
            $this->Cell(39.7, 3.6, strtoupper(utf8_decode($address->phone)), $lineas, 0, 'C');


            $enrollment_level = Grade::findOrfail($enrollment->group->grade_id)->academic_level_id;
            $enrollment_grade = $enrollment->group->grade_id;
            $this->ln(8);
            $this->Cell(188.9, 3.6, "", $lineas, 0, 'C');
            $this->Cell(9.9, 3.6, ($enrollment_level == 1 ? "X" : ""), $lineas, 0, 'C');
            $this->ln(4);
            $this->Cell(188.9, 3.6, "", $lineas, 0, 'C');
            $this->Cell(9.9, 3.6, ($enrollment_level == 2 ? "X" : ""), $lineas, 0, 'C');
            $this->ln(4);

            $this->Cell(99.4, 3.6, "", $lineas, 0, 'C');
            $this->Cell(5, 3.6, "", $lineas, 0, 'C');
            $this->Cell(4.9, 3.6, ($enrollment_grade == "4" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(5, 3.6, "", $lineas, 0, 'C');
            $this->Cell(4.9, 3.6, ($enrollment_grade == "6" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(5, 3.6, "", $lineas, 0, 'C');
            $this->Cell(4.9, 3.6, ($enrollment_grade == "7" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(5, 3.6, "", $lineas, 0, 'C');
            $this->Cell(4.9, 3.6, ($enrollment_grade == "8" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(5.2, 3.6, "", $lineas, 0, 'C');
            $this->Cell(4.9, 3.6, ($enrollment_grade == "9" ? "X" : ""), $lineas, 0, 'C');

            $this->Cell(39.8, 3.6, "", $lineas, 0, 'C');
            $this->Cell(9.9, 3.6, ($enrollment_level == 3 ? "X" : ""), $lineas, 0, 'C');

            $this->ln(4);

            ## Información Académica
            $result_id = $enrollment->enrollment_result_id;
            $this->Cell(9.9, 3.6, strtoupper(utf8_decode('')), $lineas, 0, 'C');
            $this->Cell(9.9, 3.6, '', $lineas, 0, 'C');
            $this->Cell(49.7, 3.6, '', $lineas, 0, 'C');


            $this->Cell(9.9, 3.6, ($result_id == 2 ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(9.9, 3.6, ($result_id == 3 ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(9.9, 3.6, ($result_id == 4 ? "X" : ""), $lineas, 0, 'C');

            $this->Cell(5, 3.6, "", $lineas, 0, 'C');
            $this->Cell(4.9, 3.6, ($enrollment_grade == "10" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(5, 3.6, "", $lineas, 0, 'C');
            $this->Cell(4.9, 3.6, ($enrollment_grade == "11" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(5, 3.6, "", $lineas, 0, 'C');
            $this->Cell(4.9, 3.6, ($enrollment_grade == "12" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(5, 3.6, "", $lineas, 0, 'C');
            $this->Cell(4.9, 3.6, ($enrollment_grade == "13" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(5.2, 3.6, "", $lineas, 0, 'C');
            $this->Cell(4.9, 3.6, ($enrollment_grade == "14" ? "X" : ""), $lineas, 0, 'C');

            $this->ln(12);
            #Información Académica


            $subsidiado = $student->academicInformation->has_subsidy;
            //$interno = strtoupper(utf8_decode($estudiante['interno']));
            //$otro_modelo = strtoupper(utf8_decode($estudiante['otro_modelo']));
            $caracter = AcademicInformation::findOrfail($student->academicInformation->id)->academic_character_id;
            $especialidad = $student->academicInformation->academicSpecialty->id;

            $this->Cell(9.9, 3.6, ($subsidiado == "1" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(9.9, 3.6, ($subsidiado == "0" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(9.9, 3.6, (''), $lineas, 0, 'C');
            $this->Cell(9.9, 3.6, (''), $lineas, 0, 'C');

            $this->Cell(9.9, 3.6, (''), $lineas, 0, 'C');
            $this->Cell(9.9, 3.6, (''), $lineas, 0, 'C');
            $this->Cell(19.9, 3.6, (''), $lineas, 0, 'C');

            $this->Cell(9.9, 3.6, ($enrollment_grade == "15" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(10, 3.6, ($enrollment_grade == "16" ? "X" : ""), $lineas, 0, 'C');

            $this->Cell(9.9, 3.6, ($caracter == '1' ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(10, 3.6, ($caracter == '2' ? "X" : ""), $lineas, 0, 'C');

            $this->Cell(19.87, 3.6, ($especialidad == '1' ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(19.87, 3.6, ($especialidad == "2" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(20, 3.6, ($especialidad == "3" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(19.87, 3.6, ($especialidad == "4" ? "X" : ""), $lineas, 0, 'C');

            #Sistema de salud
            $this->ln(12);
            $this->Cell(49.7, 3.6, strtoupper($student->medicalInformation->eps->name), $lineas, 0, 'C');
            $this->Cell(49.7, 3.6, strtoupper($student->medicalInformation->blood_type->ips), $lineas, 0, 'C');
            $this->Cell(49.7, 3.6, strtoupper($student->medicalInformation->blood_type->blood_type), $lineas, 0, 'C');
            $this->Cell(49.7, 3.6, strtoupper($student->medicalInformation->blood_type->ars), $lineas, 0, 'C');

            #Programas Especiales
            $victima_conflicto = $student->displacement->victimOfConflict->id;
            $this->ln(8);
            $this->Cell(39.78, 3.6, "", $lineas, 0, 'C');
            $this->Cell(19.89, 3.6, ($victima_conflicto == "1" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(79.56, 3.6, "", $lineas, 0, 'C');
            $this->Cell(39.78, 3.6, "", $lineas, 0, 'C');
            $this->Cell(19.89, 3.6, "", $lineas, 0, 'C');

            $this->ln(4);
            $this->Cell(39.78, 3.6, "", $lineas, 0, 'C');
            $this->Cell(19.89, 3.6, ($victima_conflicto == "3" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(79.56, 3.6, "", $lineas, 0, 'C');
            $this->Cell(10, 3.6, "", $lineas, 0, 'C');
            $this->Cell(10, 3.6, "", $lineas, 0, 'C');
            $this->Cell(19.89, 3.6, "", $lineas, 0, 'C');
            $this->Cell(10, 3.6, "", $lineas, 0, 'C');
            $this->Cell(10, 3.6, "", $lineas, 0, 'C');

            $this->ln(4);
            $this->Cell(39.78, 3.6, "", $lineas, 0, 'C');
            $this->Cell(19.89, 3.6, ($victima_conflicto == "2" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(39.3, 8, strtoupper(utf8_decode('')), $lineas, 0, 'C');
            $this->Cell(39.9, 8, strtoupper(utf8_decode('')), $lineas, 0, 'C');

            if (!isset($student->displacement->expulsion_date)) {
                $fecha_expulsor = array(0 => "", 1 => "", 2 => "");;
            } else {
                $fecha_expulsor = explode("-", $student->displacement->expulsion_date);
            }

            $this->Cell(10, 8, $fecha_expulsor[2], $lineas, 0, 'C');
            $this->Cell(10, 8, $fecha_expulsor[1], $lineas, 0, 'C');
            $this->Cell(19.89, 8, $fecha_expulsor[0], $lineas, 0, 'C');
            #certificado
            $certificado = strtoupper(utf8_decode($student->displacement->certificate));
            $this->Cell(10, 8, ($certificado != "" ? "X" : ""), $lineas, 0, 'C');
            $this->Cell(10, 8, ($certificado == "" ? "X" : ""), $lineas, 0, 'C');

            $this->ln(4);
            $this->Cell(39.78, 3.6, "", $lineas, 0, 'C');
            $this->Cell(19.89, 3.6, ($victima_conflicto == "5" ? "X" : ""), $lineas, 0, 'C');

            $this->ln(8);

            #Situación Socioeconomica**********************

            $fuente_recurso = strtoupper(utf8_decode(''));
            $opcion = strtoupper(utf8_decode(''));

            $this->Cell(141.2,3.6,"",$lineas,0,'C');
            $this->Cell(9.94,3.6,($fuente_recurso=="1"?"X":""),$lineas,0,'C'); #FNR
            $this->ln(4);
            $this->Cell(141.2,3.6,"",$lineas,0,'C');
            $this->Cell(9.94,3.6,($fuente_recurso=="2"?"X":""),$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(8,3.6,($opcion=="1"?"X":""),$lineas,0,'C');
            $this->ln(4);
            $this->Cell(141.2,3.6,"",$lineas,0,'C');
            $this->Cell(9.94,3.6,($fuente_recurso=="3"?"X":""),$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(8,3.6,($opcion=="2"?"X":""),$lineas,0,'C');

            $this->ln(4);
            $this->Cell(29.83,8,strtoupper(utf8_decode($student->socioeconomicInformation->sisben_number)),$lineas,0,'C');
            $this->Cell(29.83,8,strtoupper(utf8_decode($student->socioeconomicInformation->sisben_level)),$lineas,0,'C');

            $estrato = strtoupper(utf8_decode($student->socioeconomicInformation->stratum_id));
            $this->Cell(6,8,($estrato=="1"?"X":""),$lineas,0,'C');
            $this->Cell(6,8,($estrato=="2"?"X":""),$lineas,0,'C');
            $this->Cell(6,8,($estrato=="3"?"X":""),$lineas,0,'C');
            $this->Cell(6,8,($estrato=="4"?"X":""),$lineas,0,'C');
            $this->Cell(6,8,($estrato=="5"?"X":""),$lineas,0,'C');
            $this->Cell(6,8,($estrato=="6"?"X":""),$lineas,0,'C');
            $this->Cell(6,8,($estrato=="OTRO"?"X":""),$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(9.94,3.6,($fuente_recurso=="4"?"X":""),$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(8,3.6,($opcion=="3"?"X":""),$lineas,0,'C');
            $this->ln(4);

            $this->Cell(101.5,3.6,"",$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(9.94,3.6,($fuente_recurso=="5"?"X":""),$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(8,3.6,($opcion=="4"?"X":""),$lineas,0,'C');

            #Territorialidad*******************

            $this->ln(16);
            $this->Cell(69.6,3.6,strtoupper(utf8_decode($student->territorialty->guard)),$lineas,0,'C');
            $negritudes = strtoupper(utf8_decode(''));
            $this->Cell(19.9,3.6,($negritudes=="S"?"X":""),$lineas,0,'C');
            $this->Cell(19.9,3.6,($negritudes=="N"?"X":""),$lineas,0,'C');
            $this->Cell(69.6,3.6,strtoupper(utf8_decode($student->territorialty->ethnicity)),$lineas,0,'C');
            $this->Cell(19.9,3.6,"",$lineas,0,'C');

            #Discpacidades ****************************************************


            $this->ln(12);
            $discapacidades = strtoupper(utf8_decode(''));
            $capacidades = strtoupper(utf8_decode(''));

            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,($discapacidades=="3"?"X":""),$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,"",$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,($discapacidades=="4"?"X":""),$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,($capacidades=="1"?"X":""),$lineas,0,'C');

            $this->ln(4);
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,"",$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,"",$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,"",$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,($capacidades=="7"?"X":""),$lineas,0,'C');

            $this->ln(4);
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,"",$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,($discapacidades=="1"?"X":""),$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,"",$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,($capacidades=="5"?"X":""),$lineas,0,'C');

            $this->ln(4);
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,($discapacidades=="10"?"X":""),$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,"",$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,"",$lineas,0,'C');
            $this->Cell(39.8,3.6,"",$lineas,0,'C');
            $this->Cell(10,3.6,"",$lineas,0,'C');

            #Informacion Familiar
            $family = $student->family[0];
            $identification_family = $student->family[0]->identification;
            $tipo_doc_familiar = $student->family[0]->identification->identification_type_id;
            $name_family = strtoupper(utf8_decode($student->family[0]->name)." ".utf8_decode($student->family[0]->last_name));
            $family_city_expedition = $student->family[0]->identification->city_expedition;
            $family_province_expedition = $student->family[0]->identification->city_expedition->province;
            $family_address = $student->family[0]->address;

            $this->ln(16);
            $this->Cell(5,3.6,($tipo_doc_familiar=='1'?"X":""),$lineas,0,'C');
            $this->Cell(5,3.6,($tipo_doc_familiar=='3'?"X":""),$lineas,0,'C');
            $this->Cell(5,3.6,($tipo_doc_familiar=='2'?"X":""),$lineas,0,'C');
            $this->Cell(5,3.6,($tipo_doc_familiar=="CE"?"X":""),$lineas,0,'C');

            $this->Cell(19.9,3.6,$student->family[0]->identification->identification_number,$lineas,0,'C'); # documento
            $this->Cell(19.9,3.6,strtoupper(utf8_decode($family_province_expedition->name)),$lineas,0,'C'); # departamento Exped
            $this->Cell(19.9,3.6,strtoupper(utf8_decode($family_city_expedition->name)),$lineas,0,'C');
            $this->Cell(119.3,3.6,$name_family,$lineas,0,'C');

            $this->ln(8);

            $parentesco =  Student::getParents($student->id, $student->family[0]->id)->relationship_id;


            $this->Cell(89.5,3.6," ",$lineas,0,'C');
            $this->Cell(14.9,3.6," ",$lineas,0,'C');
            $this->Cell(5,3.6,($parentesco=="1"?"X":""),$lineas,0,'C');
            $this->Cell(14.9,3.6," ",$lineas,0,'C');
            $this->Cell(5,3.6,($parentesco=="2"?"X":""),$lineas,0,'C');
            $this->Cell(14.9,3.6," ",$lineas,0,'C');
            $this->Cell(5,3.6,(($parentesco=="9" || $parentesco=="10")?"X":""),$lineas,0,'C');
            $this->Cell(14.9,3.6," ",$lineas,0,'C');
            $this->Cell(5,3.6,(($parentesco=="5" || $parentesco=="6")?"X":""),$lineas,0,'C');

            $this->ln(4);
            $this->Cell(29.8,3.6,strtoupper(utf8_decode($family_address->address)),$lineas,0,'C'); #Direccion
            $this->Cell(29.8,3.6,$family_address->phone,$lineas,0,'C');
            $this->Cell(29.8,3.6,$family_address->mobil,$lineas,0,'C');
            $this->Cell(14.9,3.6," ",$lineas,0,'C');
            $this->Cell(5,3.6,(($parentesco=="3" || $parentesco=="4")?"X":""),$lineas,0,'C');
            $this->Cell(14.9,3.6," ",$lineas,0,'C');
            $this->Cell(5,3.6,($parentesco==""?"X":""),$lineas,0,'C');
            $this->Cell(14.9,3.6," ",$lineas,0,'C');
            $this->Cell(24.8,3.6,"",$lineas,0,'C');

            $acudiente = "S";
            $this->Cell(14.9,3.6,($acudiente=="S"?"X":""),$lineas,0,'C');
            $this->Cell(14.9,3.6,($acudiente=="N"?"X":""),$lineas,0,'C');

            $this->ln(48);
            $message = "En mi calidad de Rector de la ".$institution->name." certifico que se anexa al presente";
            $message2 = "fotocopia delos certificados de estudio de los años anterior y del documento de identidad";
            $this->Cell(196,4,strtoupper(utf8_decode($message)),0,0,'C');
            $this->ln(4);
            $this->Cell(196,4,strtoupper(utf8_decode($message2)),0,0,'C');

        }
    }


}