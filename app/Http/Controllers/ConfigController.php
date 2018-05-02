<?php

namespace App\Http\Controllers;

use App\ConfigInstitution;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Institution;

class ConfigController extends Controller
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

    public function getConfigInstitution()
    {
        $config = ConfigInstitution::where('config_institution.institution_id', '=', $this->institution->id)
            ->get();
        return $config;
    }

}
