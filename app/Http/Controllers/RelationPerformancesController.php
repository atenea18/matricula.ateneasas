<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NotesParametersPerformances;
use App\Helpers\Evaluation\RelationshipPerformances\MainRelationship;
use App\Helpers\Evaluation\RelationshipPerformances\ParamsRelationship;

class RelationPerformancesController extends Controller
{
    private $teacher = null;
    private $institution = null;
    private $params = null;

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

    public function store(Request $request)
    {
        $requestAux = (object)$request->data;
        $params = new ParamsRelationship($requestAux);
        $mainRelationship = new MainRelationship($params);

        return $mainRelationship->store();
    }

    public function get(Request $request)
    {

        $params = new ParamsRelationship($request);
        $mainRelationship = new MainRelationship($params);

        return $mainRelationship->get();
    }

    public function delete(Request $request)
    {
        $requestAux = (object)$request->data;
        $params = new ParamsRelationship($requestAux);
        $mainRelationship = new MainRelationship($params);

        return  $mainRelationship->delete();

    }

}
