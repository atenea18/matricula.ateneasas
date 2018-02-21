<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Institution;
use App\Group;

class GroupController extends ApiController
{
    public function index(Institution $institution)
    {
    	$groups = $institution->headquarters()
    	->with('groups')
    	->get()
    	->pluck('groups')
    	->collapse();
        // ->sortBy('groups.grade_id');

    	return $this->showAll($groups);
    }

    public function students(Group $group)
    {
    	$students = $group->enrollments()
    	->with('student')
    	->with('student.identification.identification_type')
    	->with('student.address')
    	->get()
    	->pluck('student');

    	return $this->showAll($students);
    }

}
