<?php

namespace App\Http\Controllers;

use App\CustomAreas;
use App\Group;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicAssignmentController extends Controller
{
    public function viewSubgroup()
    {
        return view('institution.partials.assignment.subgroup');
    }

    public function getAreas($institution_id = 6, $grade_id = 4)
    {

        if (request()->ajax()) {

            $areas = DB::table('areas')
                ->get();
            return $areas;
        }
        return [];
    }

    public function getSubgroupsByGrade(Request $request)
    {

        $institution_id = Auth::guard('web_institution')->user()->id;

        $subgroups = DB::table('sub_group')
            ->join('headquarter', 'headquarter.id', '=', 'sub_group.headquarter_id')
            ->select('sub_group.id', 'sub_group.name', 'headquarter.name as headquarter_name',
                'sub_group.grade_id','sub_group.section_id')
            ->where('sub_group.grade_id', '=', $request->grade_id)
            ->where('headquarter.institution_id', '=', $institution_id)
            ->get();
        return $subgroups;
    }

    public function getAsignatures()
    {
        $institution_id = Auth::guard('web_institution')->user()->id;

        if (request()->ajax()) {
            $asignatures = DB::table('asignatures')
                ->get();
            return $asignatures;
        }
        return [];
    }

    public function getSubjectsType()
    {
        if (request()->ajax()) {
            $subjects_type = DB::table('subjects_type')->get();

            return $subjects_type;
        }
        return "error";
    }

    public function getTeachers()
    {

        $institution_id = Auth::guard('web_institution')->user()->id;

        if (request()->ajax()) {
            $teachers = DB::table('teachers')
                ->select('teachers.id', DB::raw('CONCAT(managers.last_name," ",managers.name) as name'))
                ->join('managers', 'managers.id', '=', 'teachers.manager_id')
                ->where('teachers.institution_id', '=', $institution_id)
                ->where('teachers.school_year_id', '=', 1)
                ->orderBy('managers.last_name', 'asc')
                ->get();

            return $teachers;
        }
        return "error";
    }

    public function storeSubGroupPensumByGroup(request $request)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        $pensum = $request->data;

        if ($request->ajax()) {

            foreach ($pensum as $row) {

                try {
                    $value = DB::table('sub_group_pensum')->insertGetId(
                        [
                            'percent' => $row['percent'],
                            'ihs' => $row['ihs'],
                            'order' => $row['order'],
                            'sub_group_id' => $row['sub_group_id'],
                            'teacher_id' => $row['teacher_id'],
                            'areas_id' => $row['areas_id'],
                            'subjects_type_id' => $row['subjects_type_id'],
                            'asignatures_id' => $row['asignatures_id'],
                            'schoolyear_id' => 1
                        ]
                    );
                } catch (\Exception $e) {
                    $value = 0;
                }
            }

            return $value;
        }

        return $pensum;


    }

    public function getSubgroupPensum(Request $request)
    {


        $institution_id = Auth::guard('web_institution')->user()->id;

        try {
            $sub_group_pensum = DB::table('sub_group_pensum')
                ->select('sub_group_pensum.areas_id', 'areas.name as name_area', 'subjects_type.name as subjects_type_name',
                    'sub_group.section_id')
                ->join('areas', 'areas.id', '=', 'sub_group_pensum.areas_id')
                ->join('subjects_type', 'subjects_type.id', '=', 'sub_group_pensum.subjects_type_id')
                ->where('sub_group_pensum.sub_group_id', '=', $request->sub_group_id)
                ->groupBy('areas_id')
                ->get();

            $asignatures = DB::table('sub_group_pensum')
                ->select('sub_group_pensum.areas_id', 'sub_group_pensum.asignatures_id', 'asignatures.name as name_asignatures', 'subjects_type.name as subjects_type_name')
                ->join('areas', 'areas.id', '=', 'sub_group_pensum.areas_id')
                ->join('asignatures', 'asignatures.id', '=', 'sub_group_pensum.asignatures_id')
                ->join('subjects_type', 'subjects_type.id', '=', 'sub_group_pensum.subjects_type_id')
                ->where('sub_group_pensum.sub_group_id', '=', $request->sub_group_id)
                ->get();

        } catch (\Exception $e) {
            return [];
        }

        #representa una lista de areas con sus asignaturas
        $collection = [];

        foreach ($sub_group_pensum as $key => $sub_group_pensum) {

            $collection_sub_group_pensum = [];
            foreach ($asignatures as $keyAsignature => $asignature) {
                if ($sub_group_pensum->areas_id == $asignature->areas_id) {
                    array_push($collection_sub_group_pensum, $asignature);
                    unset($asignatures[$keyAsignature]);
                }
            }
            $sub_group_pensum->asignatures = $collection_sub_group_pensum;
            array_push($collection, $sub_group_pensum);
        }


        return $collection;
    }

}
