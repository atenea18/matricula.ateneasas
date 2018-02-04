<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\Group;
use PDF;
use Auth;

class PdfController extends Controller
{
    public function inscription($group_id, $year)
    {
    	$institution = Auth::guard('web_institution')->user();
    	// $students = Student::getGroup($group_id);
    	$group = Group::findOrFail($group_id);

        $students = $group->enrollments()
        ->with('schoolYear')
        ->with('student')
        // ->where('schoolYear.year','=',$year)
        ->get()
        ->pluck('student')
        ->sortBy('last_name');

        // dd($students);
    	// return view('pdf.inscription', compact('students', 'group', 'institution'));
    	// dd($group->headquarter->institution);
    	$pdf = PDF::loadView('pdf.inscription', compact('students', 'group', 'institution'));
    	return $pdf->stream('Grupo.pdf');
    }
}
