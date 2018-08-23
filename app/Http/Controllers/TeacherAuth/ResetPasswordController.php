<?php

namespace App\Http\Controllers\TeacherAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Manager;

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
    	$token = $this->checkStateToken($request->token);

        // return response()->json([
        //     'toke'  =>  $token,
        //     'request'=> $request->all()
        // ]);

        if($token->username != $request->username)
        {
            flash('El nombre del usuario es incorrecto')->error();
            return back();
        }

        $request->validate([
            'password'  =>  'required|confirmed',
        ],[
            'password.required' =>  'La contraseña es requerida',
            'password.confirmed'=>  'Las constraseñas no coinciden'
        ]);

        $manager = Manager::where('username', $request->username)->first();
        $manager->password = bcrypt($request->password);
        $manager->update();

        DB::table($this->table)->where('username', $request->username)->delete();

        flash('Contraseña cambiada con exito')->success();

        return redirect()->route('teacher.login');
    }

    protected function checkStateToken($token)
    {
    	return DB::table($this->table)->where('token', $token)->first();
    }
}
