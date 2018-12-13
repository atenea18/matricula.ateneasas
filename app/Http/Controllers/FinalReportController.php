<?php

namespace App\Http\Controllers;

use App\FinalReport;
use App\FinalReportAsignature;
use App\ScaleEvaluation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class FinalReportController extends Controller
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

    public function dispatchers(Request $request)
    {
        $data = $request->data;

        if ($data['is_report_final'] == "true") {
            $this->storeGeneral($data);
        }

        if ($data['is_report_asignatures'] == "true") {
            $this->storeAsignatures($data);
        }

    }

    private function storeGeneral($data)
    {
        $report = null;

        foreach ($data['enrollments'] as $enrollment) {
            $report = FinalReport::where('enrollment_id', '=', $enrollment['id'])->first();
            if (!$report) {
                try {
                    $report = new FinalReport();
                    $report->average = round($enrollment['average'], 1);
                    $report->keep_going = "SI";
                    $report->enrollment_id = $enrollment['id'];
                    $report->rating = $enrollment['rating'];
                    $this->getDescriptionEnrollment($report, $enrollment, $data);
                    $report->save();
                } catch (\Exception $e) {

                }
            } else {
                $sameReport = FinalReport::find($report->id);
                $sameReport->average = round($enrollment['average'], 1);
                $sameReport->keep_going = "SI";
                $sameReport->rating = $enrollment['rating'];
                $this->getDescriptionEnrollment($sameReport, $enrollment, $data);
                $sameReport->update();
            }
        }

        return 'Yes';
    }

    private function storeAsignatures($data)
    {


        foreach ($data['enrollments'] as $enrollment) {
            foreach ($enrollment['finalReport'] as $final) {
                $report = null;
                $report = FinalReportAsignature::where('enrollment_id', '=', $enrollment['id'])
                    ->where('asignatures_id', '=', $final['asignatures_id'])->first();
                if (!$report) {
                    try {
                        $report = new FinalReportAsignature();
                        $report->value = round($final['value'], 1);
                        $report->enrollment_id = $enrollment['id'];
                        $report->asignatures_id = $final['asignatures_id'];
                        $report->report = $final['report'];
                        $report->save();
                    } catch (\Exception $e) {

                    }
                } else {
                    $sameReport = FinalReportAsignature::find($report->id);
                    $sameReport->value = round($final['value'], 1);
                    $sameReport->report = $final['report'];
                    $sameReport->update();
                }
            }
        }

        return 'Yes';
    }

    public function getDescriptionEnrollment(&$report, $enrollment, $data)
    {
        $condition = $data['condition'];
        $condition_number = $data['condition_number'];
        $subject = $data['areas'];

        switch ($condition) {
            case "0":
                if ($enrollment['failedSubjects']['number'] == $condition_number) {
                    $report->description = self::getMessageReprobated($condition_number, $condition, $subject);
                    $report->news_id = 45;
                    return true;
                }
                break;
            case "1":
                if ($enrollment['failedSubjects']['number'] >= $condition_number) {
                    $report->description = self::getMessageReprobated($condition_number, $condition, $subject);
                    $report->news_id = 45;
                    return true;
                }
                break;
            default:
                if ($enrollment['failedSubjects']['number'] <= $condition_number) {
                    $report->description = self::getMessageReprobated($condition_number, $condition, $subject);
                    $report->news_id = 45;
                    return true;
                }
        }

        $report->description = self::getMessageAprobated();
        $report->news_id = 39;
        return true;
    }

    static public function getMessageAprobated()
    {
        return "PROMOVIDO AL SIGUIENTE GRADO";
    }

    static public function getMessageReprobated($condition_number, $condition, $subject)
    {
        $message = "REPROBADO CON ";
        return $message . self::getSubject($subject) . self::getCondition($condition_number, $condition);
    }

    static public function getCondition($condition_number, $condition)
    {
        if ($condition == "0")
            return " IGUAL A " . $condition_number;
        if ($condition == "1")
            return " MAYOR/IGUAL A " . $condition_number;
        if ($condition == "2")
            return " MENOR/IGUAL A " . $condition_number;
    }

    static public function getSubject($is_subject)
    {
        return $is_subject == "true" ? "ÁREAS" : "ASIGNATURAS";
    }


    public function updateOvercomingAsignatures(Request $request)
    {
        $data = $request->data;

        $report = null;
        $report = FinalReportAsignature::where('id', '=', $data['final_report_asignature_id'])->first();
        $scale = ScaleEvaluation::getBasicScale($this->institution);

        $sameReport = FinalReportAsignature::find($report->id);
        try {
            $sameReport->overcoming = $data['value'];
            if ($sameReport->overcoming < $scale->rank_start)
                $sameReport->report = "REP";
            if ($sameReport->overcoming >= $scale->rank_start)
                $sameReport->report = "APR";

            $sameReport->update();
        } catch (\Exception $e) {

        }


        return $sameReport;
    }
}
