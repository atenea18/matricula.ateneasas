<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

class HomeController extends Controller
{
    public function index()
    {
    	$manager = Auth::guard('teachers')->user();

    	return View('teacher.partials.home')
    	->with('manager',$manager);
    }
}
