<?php

namespace App\Http\Controllers;

use App\SubGroupPensum;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Student;
use App\Group;
use App\Grade;
use App\Schoolyear;
use App\Institution;
use App\Subgroup;
use App\GroupPensum;
use App\NotesFinal;
use App\Manager;
use App\Headquarter;

use PDF;
use Auth;

use setasign\Fpdi\Fpdi;

// 
use App\Pdf\Sheet\StudentAttendance;
use App\Pdf\Constancy\Study as ConstancyStudy;
use App\Pdf\Sheet\EvaluationSheet;
use App\Pdf\Sheet\TeacherSheet;
use App\Pdf\Sheet\ParentSheet;
use App\Pdf\Statistics\Consolidate;

// 
use App\Helpers\EvaluationSheetHelper;

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

        $this->merge($path, 'lista de asistencia - '.$group->name, 'l');
    }

    public function attendances(Request $request)
    {
        // 
        $path = "./pdf/".time()."-".$request->institution_id.$request->year."-attendance/";

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
        $this->merge($path, 'lista de asistencia_'.time()."_".$request->institution_id, 'l');
    }

    public function attendanceTeacher(Request $request)
    {
        $headquarter = Headquarter::findOrFail($request->headquarter_id_ta);
        $group = null;
        $managers = null;

        if(isset($request->group_id_ta))
        {
            $managers = Manager::getByGroup($request->group_id_ta);
            $group = Group::findOrFail($request->group_id_ta);
        }
        else
        {
            $managers = Manager::getByHeadquarter($request->headquarter_id_ta);
        }
        
        // dd($managers);
        $pdf = new TeacherSheet('p', 'mm', 'letter');
        $pdf->setInstitution($headquarter->institution);
        $pdf->setHeadquarter($headquarter);
        $pdf->setGroup($group);
        $pdf->setEvent($request->event);
        $pdf->setData($managers);
        $pdf->create();
        $pdf->Output("file.pdf", "D"); 
    }

    public function attendanceParent(Request $request)
    {
        $group = Group::findOrFail($request->group_id_pa);
        $students = $group->enrollments()
        ->with('student')
        ->with('student.identification.identification_type')
        ->with('student.address')
        ->with('student.family.address')
        ->get()
        ->sortBy('student.last_name')
        ->pluck('student');

        // dd($students);

        $pdf = new ParentSheet('p', 'mm', 'letter');
        $pdf->setInstitution($group->headquarter->institution);
        $pdf->setHeadquarter($group->headquarter);
        $pdf->setGroup($group);
        $pdf->setEvent($request->event);
        $pdf->setData($students);
        $pdf->create();
        $pdf->Output("file.pdf", "D"); 
    }

    public function evaluationPdf(Request $request)
    {
        $path = "./pdf/".time()."-".$request->institution_id.$request->year."-evaluationSheet/";
        $institution = Institution::findOrFail($request->institution_id);
        $schoolYear = Schoolyear::where('year','=',$request->year)->first();
        $parameters = $institution->evaluationParameters()
        ->with('criterias')->with('schoolYear')->where([
            ['school_year_id', '=', '1'],['group_type', '=', $request->group_type]
        ])->get();
        $periods = $institution->periods()
        ->with('period')->orderBy('periods_id')->get()->pluck('period')->unique()->values()->pluck('name','id');

        // dd(count($parameters));
        if(!file_exists($path))
        {   
            mkdir($path);
        }

        foreach($request->groups as $key => $group_id)
        {

            $helper = new EvaluationSheetHelper($group_id, $request, $periods);

           $pensums = $helper->getPensums();

           if(count($pensums) > 0)
           {
                foreach($pensums['pensums'] as $key => $pensum) { 

                    // dd($pensum);
                    $evaluationSheet = new EvaluationSheet($request->orientation, 'mm', $request->papper);
                    $evaluationSheet->institution = $institution;
                    $evaluationSheet->group = $pensums['group'];
                    $evaluationSheet->parameters = $parameters;
                    $evaluationSheet->asignatureName = $pensum['asignature'];
                    $evaluationSheet->pensum = $pensum;
                    $evaluationSheet->periods = $periods;
                    $evaluationSheet->create();
                    $evaluationSheet->Output($path.$group_id.$key.".pdf", "F");
                }
           }

        }

        $this->merge($path, 'Planilla-Evaluacion'.time()."_".$request->institution_id, $request->orientation);        
    }

    public function constancyStudy(Request $request)
    {
        $path = "./pdf/".time()."-".$request->institution_id.$request->year."-constancyStudy/";
        $institution = Institution::findOrFail($request->institution_id);
        $constancy = $institution->constancies()->where('type_id', '=', 1)->first();
        
        $constancy_Study = new ConstancyStudy($request->orientation, 'mm', $request->papper);
        $constancy_Study->SetMargins(30, 25 , 30); 
        $constancy_Study->constancy = $constancy;
        $constancy_Study->institution = $institution;

        if(!file_exists($path))
        {   
            mkdir($path);
        }

        if(isset($request->group_id_cs))
        {
            $group = Group::findOrFail($request->group_id_cs);

            if(!isset($request->students) && $group != null){
                $constancy_Study->createByGroup($group);
            }
            else
            {
                $constancy_Study->createByStudents($request->students);
            }
        }
        else
        {   
            $grade = Grade::findOrFail($request->grade_id_cs);

            $constancy_Study->createByGrade($grade);
        }

        $constancy_Study->Output($path.$institution->id."constancia.pdf", "F");
        
        $this->merge($path, 'Constancias', $request->orientation);
    }

    public function printConsolidate(Request $request)
    {
        if ($request->is_subgroup == "false") {
            $pensum = GroupPensum::find($request->group_id);
        }else{
            $pensum = SubGroupPensum::find($request->group_id);
        }

        $notes = NotesFinal::getConsolidate($request);

        // return response()->json($notes);

        $consolidate = new Consolidate('l', 'mm', 'letter');
        $consolidate->institution = Institution::findOrFail($request->institution_id);

        if ($request->is_subgroup == "false") {
            $consolidate->group = Group::findOrFail($request->group_id);
        }else{
            $consolidate->group = Subgroup::findOrFail($request->group_id);
        }

        $consolidate->asignatures = $pensum;
        $consolidate->content = $notes;
        $consolidate->create();

        $consolidate->Output("Consolidado-".time().".pdf", "D");
    }
}
