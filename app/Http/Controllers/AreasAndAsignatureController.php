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
            $custom_areas = DB::table('custom_areas')
                ->select('areas_id')
                ->get();

            $array = [];
            foreach ($custom_areas as $key => $value)
                $array[$key] = $value->areas_id;

            $areas = DB::table('areas')
                ->whereNotIn('id', $array)
                ->get();
            return $areas;
        }

        return "error";
    }

    public function getAsignatures()
    {
        if (request()->ajax()) {
            $custom_asignatures = DB::table('custom_asignatures')
                ->select('asignatures_id')
                ->get();
            $array = [];
            foreach ($custom_asignatures as $key => $value)
                $array[$key] = $value->asignatures_id;

            $asignatures = DB::table('asignature')
                ->whereNotIn('id', $array)
                ->get();

            return $asignatures;
        }
        return "error";
    }

    public function getSubjectsType()
    {
        if (request()->ajax()) {
            $subjects_type = DB::table('subjects_type')->get();

            return $subjects_type;
        }
        return "error";
    }

    public function getAsignaturesByArea($area_id)
    {
        if (request()->ajax()) {

            try {
                $value = DB::table('areas_asignatures')
                    ->select(
                        'custom_asignatures.custom_name as name', 'custom_asignatures.id'
                    )
                    ->join('custom_asignatures', 'custom_asignatures.id', '=', 'areas_asignatures.custom_asignatures_id')
                    ->where('custom_areas_id', '=', $area_id)->get();
                return $value;

            } catch (\Exception $e) {
                return [];
            }
        }
        return [];
    }

    public function getAreaByAsignature($asignature_id)
    {
        if (request()->ajax()) {
            try {
                $value = DB::table('areas_asignatures')
                    ->select(
                        'custom_areas.custom_name as name', 'custom_areas.id'
                    )
                    ->join('custom_areas', 'custom_areas.id', '=', 'areas_asignatures.custom_areas_id')
                    ->where('custom_asignatures_id', '=', $asignature_id)->get();
                return $value;

            } catch (\Exception $e) {
                return [];
            }
        }
        return [];
    }

    public function storeCustomAreas(request $request)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        $custom = json_decode($request->data, true);

        if ($request->ajax()) {

            try {
                $value = DB::table('custom_areas')->insertGetId(
                    [
                        'custom_name' => $custom['custom_name'],
                        'order' => $custom['order'],
                        'areas_id' => $custom['area_or_asig_id'],
                        'subjects_type_id' => $custom['subjects_type_id'],
                        'institution_id' => $institution_id
                    ]
                );
            } catch (\Exception $e) {
                $value = 0;
            }


            return $value;
        }
        return 0;
    }

    public function storeCustomAsignatures(request $request)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        $custom = json_decode($request->data, true);

        if ($request->ajax()) {

            try {
                $value = DB::table('custom_asignatures')->insertGetId(
                    [
                        'custom_name' => $custom['custom_name'],
                        'order' => $custom['order'],
                        'asignatures_id' => $custom['area_or_asig_id'],
                        'subjects_type_id' => $custom['subjects_type_id'],
                        'institution_id' => $institution_id
                    ]
                );
            } catch (\Exception $e) {
                $value = 0;
            }
            return $value;
        }
        return 0;

    }

    public function storeCustom(request $request)
    {

        $customs = $request->data;
        if ($request->ajax()) {
            try {
                foreach ($customs as $custom) {
                    DB::table('areas_asignatures')->insert(
                        [
                            'custom_asignatures_id' => $custom['custom_asignatures_id'],
                            'custom_areas_id' => $custom['custom_areas_id'],
                        ]
                    );
                }
            } catch (\Exception $e) {
            }
        }
        return 0;
    }

    public function deleteCustom(request $request)
    {
        $customs = $request->data;
        if ($request->ajax()) {
            try {
                foreach ($customs as $custom) {
                    DB::table('areas_asignatures')->where('custom_asignatures_id', '=', $custom['custom_asignatures_id'])
                        ->where('custom_areas_id', '=', $custom['custom_areas_id'])
                        ->delete();
                }
            } catch (\Exception $e) {
            }
        }
        return 0;
    }

    public function deleteCustomArea(request $request)
    {
        $custom = $request->data;
        if ($request->ajax()) {
            try {

                DB::table('custom_areas')
                    ->where('custom_areas.id', '=', $custom['id'])
                    ->delete();

            } catch (\Exception $e) {
            }
        }
        return 0;
    }

    public function deleteCustomAsignature(request $request)
    {
        $custom = $request->data;
        if ($request->ajax()) {
            try {

                DB::table('custom_asignatures')
                    ->where('custom_asignatures.id', '=', $custom['id'])
                    ->delete();

            } catch (\Exception $e) {
            }
        }
        return 0;
    }

    public function getCustomAreas()
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        $customAreas = DB::table('custom_areas')
            ->select('custom_areas.custom_name', 'custom_areas.id', 'subjects_type.name as subjects_type_name')
            ->join('subjects_type', 'subjects_type.id', '=', 'custom_areas.subjects_type_id')
            ->where('institution_id', '=', $institution_id)->get();
        return $customAreas;
    }

    public function getCustomAsignatures()
    {

        $institution_id = Auth::guard('web_institution')->user()->id;
        $customAsignatures = DB::table('custom_asignatures')
            ->select('custom_asignatures.custom_name', 'custom_asignatures.id', 'subjects_type.name as subjects_type_name')
            ->join('subjects_type', 'subjects_type.id', '=', 'custom_asignatures.subjects_type_id')
            ->where('institution_id', '=', $institution_id)
            ->get();
        return $customAsignatures;
    }


    public function getCustomAreasByType($subjects_type_id)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        $customAreas = DB::table('custom_areas')->where('institution_id', '=', $institution_id)
            ->where('subjects_type_id', '=', $subjects_type_id)->get();
        return $customAreas;
    }

    public function getCustomAsignaturesByType($subjects_type_id)
    {
        $custom_asignatures = DB::table('areas_asignatures')
            ->select('custom_asignatures_id')
            ->get();

        $array = [];
        foreach ($custom_asignatures as $key => $value)
            $array[$key] = $value->custom_asignatures_id;

        $institution_id = Auth::guard('web_institution')->user()->id;
        $customAsignatures = DB::table('custom_asignatures')->where('institution_id', '=', $institution_id)
            ->where('subjects_type_id', '=', $subjects_type_id)
            ->whereNotIn('id', $array)
            ->get();
        return $customAsignatures;
    }


}
