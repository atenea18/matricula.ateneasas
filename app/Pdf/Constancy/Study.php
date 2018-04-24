<?php

namespace App\Pdf\Constancy;

/**
* 
*/
use Illuminate\Support\Facades\Storage;
use Codedge\Fpdf\Fpdf\Fpdf;

use App\Student;
use App\Group;
use App\Grade;
use App\Constancy;
use App\Constancy_type;
use App\Enrollment;

/**
* 
*/
class Study extends Fpdf
{
	
	public $constancy;
	public $institution;

	function Header()
	{
		if($this->institution->picture != NULL)
			$this->Image(
				Storage::disk('uploads')->url(
					$this->institution->picture
				), 12, 14, 17, 17, "PNG");


		// PRIMERA LINEA
	    $this->SetFont('Arial','B',14);
	    // Título
	    $this->Cell(0, 6, utf8_decode($this->institution->name), 0, 0, 'C');
	    $this->Ln(6);

	    // Título
	    $this->SetFont('Arial','B',9);
	    if(count($this->institution->headquarters) > 1){
	    	$this->Cell(0,4, $this->group->headquarter->name, 0, 0, 'C');
	    	$this->Ln(4);	
	    }


	    // Título
	    $this->Cell(0, 4, "Constancia de Estudio", 0, 0, 'C');

		$this->Ln(30);
	}

	public function createByStudents($ids=array())
	{
		foreach($ids as $key => $id)
		{
			$student = Student::findOrFail($id);
			$enrollment = $student->enrollments()
			->where('enrollment.school_year_id', '=', 1)
			->first();

			$this->createConstancy($enrollment);
		}
	}

	public function createByGroup(Group $group)
	{
		$enrollments = $group->enrollments()
        ->with('student')
        ->with('student.identification.identification_type')
        ->with('group.workingday')
        ->with('schoolYear')
        ->get();

        foreach($enrollments as $key => $enrollment)
        {
        	$this->createConstancy($enrollment);
        }
	}

	public function createByGrade(Grade $grade)
	{
		$enrollments = $grade->groups()
            ->with('enrollments.student.identification.identification_type')
            ->with('enrollments.group.workingday')
            ->with('enrollments.schoolYear')
            ->get()
            ->pluck('enrollments')
            ->collapse();

        foreach($enrollments as $key => $enrollment)
        {
        	$this->createConstancy($enrollment);
        }
	}

	private function createConstancy(Enrollment $enrollment)
	{
		$this->AddPage();

		$this->showHeader();
		$this->showBody($enrollment);
		$this->showFooter();
	}

	private function showHeader()
	{
		$this->SetFont('Arial','',10);
		$this->MultiCell(0, 4, utf8_decode($this->constancy->header), 0, 'C');
		
		$this->Ln(20);

		$this->SetFont('Arial','B',12);
		$this->Cell(0, 4, "HACE CONSTAR", 0, 0, 'C');
		
		$this->Ln(20);

	}

	private function showBody(Enrollment $enrollment)
	{
		$this->SetFont('Arial','',9);
		$body = "Que {$enrollment->student->fullNameInverse} identificado con {$enrollment->student->identification->identification_type->abbreviation} {$enrollment->student->identification->identification_number} se encuentra matriculado en el grado ({$enrollment->grade->name_letter} {$enrollment->grade->name}° ) y asiste a clases en el grupo {$enrollment->group()->first()->name}, año lectivo {$enrollment->schoolYear->year} , Jornada de la {$enrollment->group()->first()->workingday->name}
		";

		$footer = "Para constancia se firma en {$enrollment->group()->first()->headquarter->address->city->name} ({$enrollment->group()->first()->headquarter->address->city->province->name}), el ".date("Y-m-d")."
		";


		$this->MultiCell(0, 4, utf8_decode($body), 0, 'L');

		$this->Ln(15);

		$this->MultiCell(0, 4, utf8_decode($footer), 0, 'L');		

		
	}

	private function showFooter()
	{

		$this->Ln(30);

		$this->SetFont('Arial','',10);

		$this->Cell(130, 4, $this->constancy->firstFirm, 0, 0, 'L');
		$this->Cell(0, 4, $this->constancy->secondFirm, 0, 0, 'L');

		$this->Ln(4);
		
		$this->SetFont('Arial','B',10);

		$this->Cell(130, 4, $this->constancy->firstRol, 0, 0, 'L');
		$this->Cell(0, 4, $this->constancy->secondRol, 0, 0, 'L');
	}

	function Footer()
	{
		// To be implemented in your own inherited class
	}
	
}