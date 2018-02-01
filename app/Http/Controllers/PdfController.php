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
    	$students = Student::getGroup($group_id);
    	$group = Group::findOrFail($group_id);

    	// return view('pdf.inscription', compact('students', 'group'));
    	// dd($group->headquarter->institution);
    	$pdf = PDF::loadView('pdf.inscription', compact('students', 'group', 'institution'));
    	return $pdf->stream('Grupo.pdf');
    }
}
