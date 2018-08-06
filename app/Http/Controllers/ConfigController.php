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
        $config = ConfigInstitution::select(
            'config_institution.name as config_institution_name', 'config_institution.id as config_institution_id',
            'config_type.description as config_type_description', 'config_type.id as config_type_id',
            'config_options.name as config_options_name', 'config_options.id as config_options_id')
            ->join('config_type', 'config_type.id', '=', 'config_institution.config_type_id')
            ->join('config_options', 'config_options.id', '=', 'config_institution.config_options_id')
            ->join('institution', 'institution.id', '=', 'config_institution.institution_id')
            ->where('config_institution.institution_id', '=', $this->institution->id)
            ->get();
        return $config;
    }

}
