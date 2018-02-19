<?php

namespace App\Http\Controllers;

use App\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function getAllGrades(Request $request){

        if ($request->ajax()){
            return Grade::all();
        }
    }


}
