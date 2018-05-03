<?php

namespace App\Http\Controllers;

use App\CustomAreas;
use App\Group;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreasAndAsignatureController extends Controller
{
    public function index()
    {
        return view('institution.partials.areas-asignature.index');
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

    public function storePensum(request $request)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        $pensum = $request->data;


        if ($request->ajax()) {
            foreach ($pensum as $row) {
                try {

                    $value = DB::table('pensum')->insertGetId(
                        [
                            'percent' => $row['percent'],
                            'ihs' => $row['ihs'],
                            'order' => $row['order'],
                            'grade_id' => $row['grade_id'],
                            'areas_id' => $row['areas_id'],
                            'subjects_type_id' => $row['subjects_type_id'],
                            'asignatures_id' => $row['asignatures_id'],
                            'institution_id' => $institution_id
                        ]
                    );
                } catch (\Exception $e) {
                    $value = 0;
                }
            }

            return $value;
        }
        return 0;
    }

    public function storePensumByGroup(request $request)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        $pensum = $request->data;

        if ($request->ajax()) {

            foreach ($pensum as $row) {

                try {
                    $value = DB::table('group_pensum')->insertGetId(
                        [
                            'percent' => $row['percent'],
                            'ihs' => $row['ihs'],
                            'order' => $row['order'],
                            'group_id' => $row['group_id'],
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


    public function getAreasByGrade($grade_id)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        try {
            $pensum = DB::table('pensum')
                ->select('pensum.areas_id', 'areas.name as name_area', 'subjects_type.name as subjects_type_name')
                ->join('areas', 'areas.id', '=', 'pensum.areas_id')
                ->join('subjects_type', 'subjects_type.id', '=', 'pensum.subjects_type_id')
                ->where('pensum.grade_id', '=', $grade_id)
                ->where('institution_id', '=', $institution_id)
                ->groupBy('areas_id')
                ->get();
            return $pensum;
        } catch (\Exception $e) {
            return [];
        }
        if (request()->ajax()) {

        }

    }

    public function getAreasByGroup($group_id)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        try {
            $pensum = DB::table('group_pensum')
                ->select('group_pensum.areas_id', 'areas.name as name_area', 'subjects_type.name as subjects_type_name')
                ->join('areas', 'areas.id', '=', 'group_pensum.areas_id')
                ->join('subjects_type', 'subjects_type.id', '=', 'group_pensum.subjects_type_id')
                ->where('group_pensum.group_id', '=', $group_id)
                ->where('schoolyear_id', '=', 1)
                ->groupBy('areas_id')
                ->get();
            return $pensum;
        } catch (\Exception $e) {
            return [];
        }
        if (request()->ajax()) {

        }
    }


    # Obtiene el pensum académico por un grupo específico
    public function getAsignaturesPensumByGrade($grade_id, $area_id)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;

        if (request()->ajax()) {

            try {
                $pensum = DB::table('pensum')
                    ->select('pensum.id', 'pensum.asignatures_id', 'asignatures.name as name_asignatures', 'subjects_type.name as subjects_type_name'
                        , 'pensum.order', 'pensum.percent', 'pensum.ihs')
                    ->join('asignatures', 'asignatures.id', '=', 'pensum.asignatures_id')
                    ->join('subjects_type', 'subjects_type.id', '=', 'pensum.subjects_type_id')
                    ->where('pensum.grade_id', '=', $grade_id)
                    ->where('pensum.institution_id', '=', $institution_id)
                    ->where('pensum.areas_id', '=', $area_id)
                    ->get();
                return $pensum;

            } catch (\Exception $e) {
                return [];
            }
        }
        return [];
    }

    public function getAsignaturesPensumByGroup($group_id, $area_id)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;

        if (request()->ajax()) {

            try {
                $pensum = DB::table('group_pensum')
                    ->select(
                        'group_pensum.id',
                        'group_pensum.asignatures_id', 'group_pensum.subjects_type_id',
                        'group_pensum.areas_id', 'group_pensum.teacher_id',
                        'asignatures.name as name_asignatures',
                        'subjects_type.name as subjects_type_name',
                        DB::raw('CONCAT(managers.last_name," ",managers.name) as name_teachers'),
                        'group_pensum.order', 'group_pensum.percent', 'group_pensum.ihs')
                    ->join('asignatures', 'asignatures.id', '=', 'group_pensum.asignatures_id')
                    ->join('subjects_type', 'subjects_type.id', '=', 'group_pensum.subjects_type_id')
                    ->leftJoin('teachers', 'teachers.id', '=', 'group_pensum.teacher_id')
                    ->leftJoin('managers', 'managers.id', '=', 'teachers.manager_id')
                    ->where('group_pensum.group_id', '=', $group_id)
                    ->where('schoolyear_id', '=', 1)
                    ->where('group_pensum.areas_id', '=', $area_id)
                    ->get();
                return $pensum;

            } catch (\Exception $e) {
                return [];
            }
        }
        return [];
    }


    public function deleteAreaPensumByGrade(request $request)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;

        $area = $request->data;
        if ($request->ajax()) {
            try {
                DB::table('pensum')
                    ->where('pensum.areas_id', '=', $area['areas_id'])
                    ->where('pensum.grade_id', '=', $area['grade_id'])
                    ->where('pensum.institution_id', '=', $institution_id)
                    ->delete();

            } catch (\Exception $e) {
            }
        }
        return 0;
    }


    public function deleteAreaPensumByGroup(request $request)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;

        $area = $request->data;
        if ($request->ajax()) {
            try {
                DB::table('group_pensum')
                    ->where('group_pensum.areas_id', '=', $area['areas_id'])
                    ->where('group_pensum.group_id', '=', $area['group_id'])
                    ->delete();

            } catch (\Exception $e) {
            }
        }
        return 0;
    }


    public function deleteAsignaturePensumByGrade(request $request)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;

        $asignature = $request->data;
        if ($request->ajax()) {
            try {
                DB::table('pensum')
                    ->where('pensum.id', '=', $asignature['id'])
                    ->delete();

            } catch (\Exception $e) {
            }
        }
        return $asignature;
    }

    public function deleteAsignaturePensumByGroup(request $request)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        $data = $request->data;

        if ($request->ajax()) {
            try {
                DB::table('group_pensum')
                    ->where('group_pensum.id', '=', $data['id'])
                    ->delete();
            } catch (\Exception $e) {
            }
        }

    }

    public function editPensumGroup(request $request)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;
        $data = $request->data;
        $value = 0;


        if ($request->ajax()) {
            try {
                $value = DB::table('group_pensum')
                    ->where('group_pensum.id', '=', $data['id'])
                    ->update(['' . $data['field'] => $data['value']]);
            } catch (\Exception $e) {
            }
        }


        return $value;

    }


    public function copyPensumByGrade(request $request)
    {
        $institution_id = Auth::guard('web_institution')->user()->id;

        $data = $request->data;
        $pensumGrade = DB::table('pensum')
            ->where('pensum.grade_id', '=', $data['grade_id'])
            ->where('pensum.subjects_type_id', '=', 1)
            ->where('pensum.institution_id', '=', $institution_id)
            ->get();

        $groups = Group::getGroupsByGrade($institution_id, $data['grade_id']);


        if ($request->ajax()) {
            foreach ($groups as $group) {
                foreach ($pensumGrade as $row) {
                    try {

                        DB::table('group_pensum')->insertGetId(
                            [
                                'code_group_pensum' => $group->id . $row->percent . $row->asignatures_id,
                                'percent' => $row->percent,
                                'ihs' => $row->ihs,
                                'order' => $row->order,
                                'group_id' => $group->id,
                                'areas_id' => $row->areas_id,
                                'subjects_type_id' => $row->subjects_type_id,
                                'asignatures_id' => $row->asignatures_id,
                                'schoolyear_id' => 1
                            ]
                        );
                    } catch (\Exception $e) {

                    }
                }
            }

        }
    }

}
