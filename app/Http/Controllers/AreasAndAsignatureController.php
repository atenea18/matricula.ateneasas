<?php

namespace App\Http\Controllers;

use App\CustomAreas;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreasAndAsignatureController extends Controller
{
    public function index()
    {
        return view('institution.partials.areas-asignature.index');
    }

    public function getAreas()
    {

        if (request()->ajax()) {
            $areas = DB::table('areas')->get();
            return $areas;
        }

        return "error";
    }

    public function getSubjectsType(){
        if (request()->ajax()) {
            $subjects_type = DB::table('subjects_type')->get();

            return $subjects_type;
        }
        return "error";
    }

    public function storeCustomAreas(request $request){
        $institution_id = Auth::guard('web_institution')->user()->id;
        $custom = json_decode($request->data, true);


        if ($request->ajax()) {

            try{
                $value = DB::table('custom_areas')->insertGetId(
                    [
                        'custom_name' => $custom['custom_name'],
                        'order' => $custom['order'],
                        'areas_id' => $custom['areas_id'],
                        'subjects_type_id' => $custom['subjects_type_id'],
                        'institution_id' => $institution_id
                    ]
                );
            }catch (\Exception $e){
                $value = 0;
            }


            return $value;
        }
        return 0;
    }

    public  function  getCustomAreas(){
        $institution_id = Auth::guard('web_institution')->user()->id;
        $customAreas = DB::table('custom_areas')->where('institution_id','=',$institution_id)->get();
        return $customAreas;
    }
}
