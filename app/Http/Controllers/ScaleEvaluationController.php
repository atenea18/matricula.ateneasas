<?php

namespace App\Http\Controllers;

use App\ScaleEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScaleEvaluationController extends Controller
{
    private $teacher = null;
    private $institution = null;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (Auth::guard('teachers')->check()) {
                $this->teacher = Auth::guard('teachers')->user()->teachers()->first();
                $this->institution = $this->teacher->institution;
            } elseif (Auth::guard('web_institution')->check()) {
                $this->institution = Auth::guard('web_institution')->user();
            }
            return $next($request);
        });
    }

    public function getScaleEvaluation(Request $request)
    {
        return ScaleEvaluation::getScaleByInstitution($this->institution->id);
    }
}
