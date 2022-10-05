<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CEOResource;
use App\Models\CEO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CEOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $ceos = CEO::all();
        return response([
            'success' => true,
            'ceos' => CEOResource::collection($ceos),
            'message' => 'CEOs obtenidos correctamente'
        ], 200);
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
        $data = $request->all();
        // Validate the data
        $validator = Validator::make($data, [
            'name' => 'required|string|max:191',
            'company_name' => 'required|string|max:191',
            'year' => 'required|string|max:191',
            'company_headquarters' => 'required|string|max:191',
            'what_company_does' => 'required|string|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }
        // Create the CEO
        $ceo = CEO::create($data);
        // Return the response
        return response([
            'success' => true,
            'ceo' => new CEOResource($ceo),
            'message' => 'CEO creado correctamente'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CEO  $cEO
     * @return \Illuminate\Http\Response
     */
    public function show(CEO $cEO)
    {
        //
        return response([
            'success' => true,
            'ceo' => new CEOResource($cEO),
            'message' => 'CEO obtenido correctamente'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CEO  $cEO
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CEO $cEO)
    {
        //
        $data = $request->all();
        // Validate the data
        $validator = Validator::make($data, [
            'name' => 'required|string|max:191',
            'company_name' => 'required|string|max:191',
            'year' => 'required|string|max:191',
            'company_headquarters' => 'required|string|max:191',
            'what_company_does' => 'required|string|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }
        // Update the CEO
        $cEO->update($data);
        // Return the response
        return response([
            'success' => true,
            'ceo' => new CEOResource($cEO),
            'message' => 'CEO actualizado correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CEO  $cEO
     * @return \Illuminate\Http\Response
     */
    public function destroy(CEO $cEO)
    {
        //
        $cEO->delete();
        return response([
            'success' => true,
            'message' => 'CEO eliminado correctamente'
        ], 200);
    }
}
