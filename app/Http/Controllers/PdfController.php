<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Student;
use App\Group;
use App\Schoolyear;

use PDF;
use Auth;

// 
use App\Pdf\Sheet\StudentAttendance;
use setasign\Fpdi\Fpdi;

class PdfController extends Controller
{

    private function merge($path, $fileName = 'merge' ,$orientation='p')
    {
        $pdi = new Fpdi();

        $dir = opendir($path);
        $files = array();
        while ($archivo = readdir($dir)) {
                
            if (!is_dir($archivo)){
                // echo $archivo."<br />";
                array_push($files, $archivo);
            }
        }

        asort($files);
        
        foreach ($files as $file) 
        { 
            $pageCount = $pdi->setSourceFile($path.'/'.$file); 

            for ($i=1; $i <= $pageCount; $i++) { 
                
                $tpl = $pdi->importPage($i);
                $pdi->addPage($orientation); 

                $pdi->useTemplate($tpl); 
            }
        }

        //ob_clean();
        $pdi->Output('D',$fileName.'.pdf');

        system('rm -rf ' . escapeshellarg($path), $retval);
    }

    public function attendance($group_id, $year)
    {
        // 
        $group = Group::findOrFail($group_id);
        $institution = $group->headquarter->institution;
        $schoolYear = Schoolyear::where('year','=',$year)->first();

        $students = $group->enrollments()
        ->with('student')
        ->with('student.state')
        ->where('school_year_id', '=', $schoolYear->id)
        ->get()
        ->pluck('student')
        ->sortBy('last_name');

        // 
        $path = "./pdf/".time()."-".$group_id.$year."/";

        if(!file_exists($path))
        {   
            mkdir($path);
        }

        $attendance = new StudentAttendance('l', 'mm', 'letter');
        $attendance->institution = $institution;
        $attendance->group = $group;
        $attendance->create($students);
        $attendance->Output($path.$group_id.".pdf", "F");

        $this->merge($path, 'lista de asistencia - '.$group->name,'l');
    }

    public function attendances(Request $request)
    {
        // 
        $path = "./pdf/".time()."-".$request->institution_id.$request->year."/";

        if(!file_exists($path))
        {   
            mkdir($path);
        }

        foreach($request->groups as $key => $group_id)
        {
            $group = Group::findOrFail($group_id);
            $institution = $group->headquarter->institution;
            $schoolYear = Schoolyear::where('year','=',$request->year)->first();

            // dd(count($institution->headquarters));
            $students = $group->enrollments()
            ->with('student')
            ->with('student.state')
            ->where('school_year_id', '=', $schoolYear->id)
            ->get()
            ->pluck('student')
            ->sortBy('last_name');

            $attendance = new StudentAttendance('l', 'mm', 'letter');
            $attendance->institution = $institution;
            $attendance->group = $group;
            $attendance->create($students);
            $attendance->Output($path.$group_id.".pdf", "F");

        }
        $this->merge($path, 'lista de asistencia_'.time()."_".$request->institution_id,'l');
    }
}
