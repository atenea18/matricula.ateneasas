<?php

namespace App\Http\Controllers;

use App\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function getAllGrades(){

        if (request()->ajax()) {
            return Grade::all();
        }
    }

    public function getEnrollmentsByGrade($group_id)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        $enrollmentsByGrade = Grade::enrollmentsByGrade($institution_id, $group_id);
        return $enrollmentsByGrade;

        if (request()->ajax()) {

        }

    }
}
