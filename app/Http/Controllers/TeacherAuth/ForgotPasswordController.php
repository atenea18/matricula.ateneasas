<?php

namespace App\Http\Controllers\TeacherAuth;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Manager;
use DB;
use Carbon\Carbon;
use App\Mail\SendNewPasswordTeacher;

class ForgotPasswordController extends Controller
{
	protected $table = 'teacher_password_resets';
    
    public function showLinkRequestForm()
    {
    	return view('teacher.auth.resetPassword');
    }

    public function sendResetLinkEmail(Request $request)
    {
    	// Validamos la información que viene por el formulario
    	$this->validateUsername($request);

    	// Obtenemos al usuario, extraemos el email y guardamos el token en la 
    	// base de datos
    	$manager = $this->sendResetLink($request);

        if(is_null($manager))
        {
            flash("Este usuario no esta registrado")->error();
            return back();
        }

        flash("Se ha enviado un correo para restablecer su contraseña, por favor verifique su dirección de correo {$manager->address->email}")->success();

    	return back();
    }

    protected function validateUsername(Request $request)
    {
    	$request->validate([
    		'username'	=>	'required'
    	],[
    		'username.required'	=>	'El nombre de usuario es requerido',
    	]);
    }

    protected function sendResetLink(Request $request)
    {
    	$manager = Manager::where('username', $request->username)->first();


    	// Si el manager es nulo retornamo null
    	if(!is_null($manager))
        {
            // Obtenemos un token
            $token = $this->getToken($manager);

            // Guardamos el token en la base de datos
            $response = $this->saveLinkToken($token, $manager->username);

            // enviamos el correo
            $this->sendNotificationEmail($manager, $token);
        }

    	return $manager;
    }

    protected function getToken(Manager $manager)
    {
    	return strtolower(str_random(64));
    } 

    protected function saveLinkToken($token, $username)
    {
    	return DB::table($this->table)->insert([
    		'username'	=>	$username,
    		'token'		=>	$token,
    		'created_at'	=>	Carbon::now(),
    	]);
    }

    protected function sendNotificationEmail($manager, $token)
    {
    	Mail::to($manager->address->email)->send(new SendNewPasswordTeacher($manager, $token));
    }
}
