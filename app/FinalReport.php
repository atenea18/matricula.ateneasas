<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinalReport extends Model
{
    protected $table = 'final_report';

    protected $fillable = [
        'code', 'average', 'overcoming', 'rating', 'keep_going', 'description', 'enrollment_id', 'news_id'
    ];

    static public function getEnrollmentsByGroup($group_id)
    {
        return FinalReport::select(
            'enrollment.id as enrollment_id', 'final_report.id as final_report_id',
            'final_report.average', 'final_report.keep_going', 'final_report.description',
            'final_report.rating', 'final_report.news_id', 'news.name as news_name', 'news.id as news_other_id')
            ->join('enrollment', 'enrollment.id', '=', 'final_report.enrollment_id')
            ->leftJoin('enrollment_news', 'enrollment_news.enrollment_id', '=', 'enrollment.id')
            ->leftJoin('news', 'news.id', '=', 'enrollment_news.news_id')
            ->join('group_assignment', 'enrollment.id', '=', 'group_assignment.enrollment_id')
            ->where('group_assignment.group_id', '=', $group_id)
            ->get();
    }

}
