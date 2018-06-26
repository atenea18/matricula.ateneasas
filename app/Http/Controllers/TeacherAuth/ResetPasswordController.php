<?php

namespace App\Http\Controllers\TeacherAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class ResetPasswordController extends Controller
{
	protected $table = 'teacher_password_resets';

    public function showResetForm($token)
    {
    	$response = $this->checkStateToken($token);

    	if(is_null($response))
    		return "Token Invalido";

    	return view('teacher.auth.password')
    	->with('token', $token);
    }

    public function reset(Request $request)
    {
    	$response = $this->checkStateToken($request->token);
    }

    protected function checkStateToken($token)
    {
    	return DB::table($this->table)->where('token', $token)->first();
    }
}
