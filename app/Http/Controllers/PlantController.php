<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;

class PlantController extends Controller
{
    public function index()
    {
        return view('plants');
    }

    public function getPlants(Request $request)
    {
        if ($request->ajax()) {
            $plants = Plant::select('id','species_name','common_name')->get();
            return datatables()->of($plants)->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $plant = new Plant;
        $plant->species_name = $request->input('species_name');
        $plant->common_name = $request->input('common_name');
        $plant->save();
        return response()->json(['success' => 'Plant created successfully.', 'species_name' => $plant->species_name]);
    }

    public function update(Request $request, string $id)
    {
        $plant = Plant::find($id);
        $plant->species_name = $request->input('species_name');
        $plant->common_name = $request->input('common_name');
        $plant->save();
        return response()->json(['success'=>'Plant updated successfully.']);
    }

    public function destroy(string $id)
    {
        $plant = Plant::find($id);
        $plant->delete();
        return response()->json(['success'=>'Plant deleted successfully.']);
    }

    public function edit(string $id)
    {
        $plant = Plant::find($id);

        if ($plant) {
            return response()->json(['success' => true, 'data' => $plant]);
        } else {
            return response()->json(['success' => false, 'message' => 'Plant not found.'], 404);
        }
    }
}
