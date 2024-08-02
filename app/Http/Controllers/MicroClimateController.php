<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MicroClimate;

class MicroClimateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $observationId)
    {
        $existing = MicroClimate::where('observation_id', $observationId)->first();
        if ($existing) {
            return response()->json(['error' => 'A record already exists for this observation.'], 400);
        }
        
        $request->validate([
            'temperature' => 'required',
            'humidity' => 'required',
            'pressure' => 'required',
        ]);

        MicroClimate::create([
            'observation_id' => $observationId,
            'temperature' => $request->temperature,
            'humidity' => $request->humidity,
            'pressure' => $request->pressure,
        ]);

        return response()->json(['success' => 'MicroClimate created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'temperature' => 'required',
            'humidity' => 'required',
            'pressure' => 'required',
        ]);

        $microclimate = MicroClimate::findOrFail($id);
        $microclimate->update($request->all());


        return response()->json(['success' => 'MicroClimate updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $microclimate = MicroClimate::findOrFail($id);
        $microclimate->delete();

        return response()->json(['success' => 'MicroClimate deleted successfully.']);
    }
}
