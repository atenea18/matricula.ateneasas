<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Hash;

use App\Manager;
use App\Identification_type;
use App\City;
use App\Gender;
use App\Zone;
use App\Address;
use App\Identification;
class SettingController extends Controller
{

    public function index()
    {
    	$manager = Auth::guard('teachers')->user();

    	$identifications = Identification_type::orderBy('id', 'ASC')->pluck('name', 'id');
		$cities = City::orderBy('name', 'ASC')->pluck('name', 'id');
		$genders = Gender::orderBy('gender', 'ASC')->pluck('gender', 'id');
		$zones = Zone::orderBy('name', 'ASC')->pluck('name', 'id');

    	return View('teacher.partials.setting.account')
    	->with('manager',$manager)
    	->with('identification_types', $identifications)
        ->with('cities', $cities)
        ->with('genders', $genders)
        ->with('zones', $zones)
        ->with('manager',$manager);
    }

    public function security()
    {
    	$manager = Auth::guard('teachers')->user();

    	return View('teacher.partials.setting.security')
    	->with('manager',$manager);
    }

    public function updateAccount(Request $request, Manager $manager)
    {

        $address = Address::findOrFail($manager->address_id);
        $identification = Identification::findOrFail($manager->identification_id);

        $identification->fill($request->all());
        $address->fill($request->all());
        
        $identification->save();
        $address->save();

        $manager->fill($request->all());
        $manager->save();

    	return response()->json($manager);
    }

    public function updatePassword(Request $request, Manager $manager)
    {
    	
    	$manager = Auth::guard('teachers')->user();

    	$request->validate([
    		'current_password'      =>  'required',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password',
        ],[
        	'current_password.required'         =>  'La contraseña actual es requerida',
            'password.required'                 => 'La contraseña es requerida',
            'password.min'                      => 'La contraseña debe contener minimo 6 caracteres',
            'password_confirmation.required'    => 'Por favor repita la contraseña',
            'password_confirmation.same'        => 'Las contraseñas no coinciden',
            'password_confirmation.min'         => 'Las contraseña debe tener mas de 6 caracteres',
        ]);

        if(!Hash::check($request->current_password, $manager->password))
        {
            return response()->json([
                'errors'    =>  [
                    'current_password'  => [
                        'La contraseña actual es incorrecta'
                    ]
                ]
            ], 422);
        }

        $manager->password = bcrypt($request->password);
        $manager->save();

    	return response()->json($manager);
    }

    public function checkEmail(Manager $manager)
    {
        $response = ($manager->address->email != null);

        return response()->json($response);
    }

    public function saveEmail(Request $request, Manager $manager)
    {
        $request->validate([
            'email' =>  'required|unique:address|email'
        ],[
            'email.required'    =>  'El correo es requediro',
            'email.unique'      =>  'Este dirección de correo electronico ya esta en uso por otra cuenta',
            'email.email'       =>  'Ingrese una dirección de correo electronico valida'
        ]);

        $manager->address()->update($request->all());

        return response()->json($manager->address);
    }
}
