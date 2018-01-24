<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Family;
use App\Identification;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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

    public function search(Request $request)
    {   
        $request->validate([
            'relationship_id' => 'required'
        ],
        [
            'relationship_id.required' => 'El parentesco es requerido'
        ]);
        
        $identification = Identification::where('identification_number','=', $request->no_document_search)->first();


        if($identification == null)
            return response()->json(
                [
                    'messages' => 'Algun error',
                    'errors' => [
                        'no_document_search' => ['No hay Resultados']
                    ]

                ], 422);

        $family = Family::where('identification_id', '=', $identification->id)->first();

        if($identification->family == null)
        {
            return response()->json(
                [
                    'messages' => 'Algun error',
                    'errors' => [
                        'no_document_search' => ['No hay Resultados']
                    ]

                ], 422);
        }

        $view = View('institution.partials.enrollment.family.familySearchTable')
                ->with('identification',$identification)
                ->with('student_id',$request->student_id)
                ->with('relationship_id',$request->relationship_id);
        return $view->render();
    }
}
