<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Institution;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $institutions = Institution::orderBy('id','DESC')->get();

        return View('admin.partials.institution.index')
                ->with('institutions',$institutions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('admin.partials.institution.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([   
            'name' => 'required',
            'email' => 'required|email|unique:institution',         
            'password' => 'required',
            'password_confirmation' => 'min:3|same:password',
            'picture' => 'image|mimes:jpeg,png,jpg',
        ],[
            'name.required' => 'El nombre de la institución es requerido',
            'email.required' => 'El correo electronico de la institución es requerido',
            'email.email' => 'Por favor, ingrese un correo electronico valido',
            'email.unique' => 'Esta dirección de correo ya esta registrada',
            'password.required' => 'La contrasena es requerida',
            'password_confirmation.same' => 'Las contraseñas no coinciden',
            'password_confirmation.min' => 'Las contraseña debe tener mas de 3 caracteres',
            'picture.image'=> 'Por favor cargue una imagen valida',
            'picture.mimes' => 'Solo se aceptan imagenes con extension (jpeg,png,jpg)',
        ]);


        $institution = new Institution($request->all());
        $institution->password = bcrypt($request->password);

        if($request->hasFile('picture')):
            $fileName = time().'_img_.'.$request->picture->getClientOriginalExtension();
            $path = $request->file('picture')->storeAs('images/institution/picture', $fileName, 'uploads');

            $institution->picture = $path;
        endif;

        $institution->save();

        return redirect()->route('institution.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $institution = Institution::findOrFail($id);

        return View('admin.partials.institution.edit')
                ->with('institution',$institution);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 
        $request->validate([   
            'name' => 'required',
            'email' => 'required|email|unique:institution,email,'.$id,
        ],[
            'name.required' => 'El nombre de la institución es requerido',
            'email.required' => 'El correo electronico de la institución es requerido',
            'email.email' => 'Por favor, ingrese un correo electronico valido',
            'email.unique' => 'Esta dirección de correo ya esta registrada',
            'picture.mimes' => 'Solo se aceptan imagenes con extension (jpeg,png,jpg)',
        ]);

        $institution = Institution::findOrFail($id);

        // 
        $old_image = $institution->picture;

        // 
        $institution->fill($request->all());

        if($request->hasFile('picture')):

            // 
            Storage::disk('uploads')->delete($old_image);

            // 
            $fileName = time().'_img_.'.$request->picture->getClientOriginalExtension();
            $path = $request->file('picture')->storeAs('images/institution/picture', $fileName, 'uploads');
            $institution->picture = $path;
        endif;


        $institution->save();

        return redirect()->route('institution.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changePassword(Request $request, $id)
    {   
        $request->validate([            
            'password' => 'required',
            'password_confirmation' => 'min:3|same:password',
        ],[
            'password.required' => 'La Contrasena es requerida',
            'password_confirmation.same' => 'Las contraseñas no coinciden',
            'password_confirmation.min' => 'Las contraseña debe tener mas de 3 caracteres',
        ]);

        $institution = Institution::findOrFail($id);
        $institution->password = bcrypt($request->password);
        $institution->save();

        return redirect()->route('institution.index');
    }

    public function getIdInstitution(){

    }
}
