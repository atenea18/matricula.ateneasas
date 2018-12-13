<?php

namespace App\Http\Controllers;

use App\EnrollmentNews;
use App\News;
use Auth;
use App\Group;
use App\GroupPensum;
use App\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
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

    public function index(){
        return View('institution.partials.news.index');
    }

    public function get(){
        return News::all();
    }

    public function registerEnrollments(Request $request){
        $data = $request->data;
        $enrollment_news = null;

        foreach ($data['enrollments'] as $enrollment){
            $enrollment_news = EnrollmentNews::where('enrollment_id', '=', $enrollment['id'])->first();
            if (!$enrollment_news) {
                $enrollment_news = new EnrollmentNews();
                $enrollment_news->date = $data['date'];
                $enrollment_news->enrollment_id = $enrollment['id'];
                $enrollment_news->news_id = $data['news_selected']['id'];
                $enrollment_news->save();
            }
            else {
                EnrollmentNews::where('enrollment_id', '=', $enrollment['id'])
                    ->update([
                        'date' => $data['date'],
                        'news_id' => $data['news_selected']['id'],
                    ]);
            }
        }
    }

    public function deleteEnrollment(Request $request){
        $data = $request->data;

        EnrollmentNews::findOrFail($data['enrollment_news_id'])->delete();
    }
}
