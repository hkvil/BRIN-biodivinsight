<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\LeafPhysiology;

class LeafPhysiologyController extends Controller
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
        
    }

    public function getLeafPhy(Request $request)
    {
        if ($request->ajax()) {
            $observationId = $request->input('observation_id');
            $leafs = LeafPhysiology::select('id', 'chlorophyll', 'nitrogen', 'leaf_moisture', 'leaf_temperature')
                        ->where('observation_id', $observationId)
                        ->get();
            return datatables()->of($leafs)->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $observationId = $request->input('observation_id');

        $request->validate([
            'chlorophyll' => 'required',
            'nitrogen' => 'required',
            'leaf_moisture' => 'required',
            'leaf_temperature' => 'required',
        ]);

        LeafPhysiology::create([
            'observation_id' => $observationId,
            'chlorophyll' => $request->chlorophyll,
            'nitrogen' => $request->nitrogen,
            'leaf_moisture' => $request->leaf_moisture,
            'leaf_temperature' => $request->leaf_temperature,
        ]);

        return response()->json(['success' => 'Leaf Physiology created successfully.']);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'chlorophyll' => 'required',
            'nitrogen' => 'required',
            'leaf_moisture' => 'required',
            'leaf_temperature' => 'required',
        ]);

        $leafPhysiology = LeafPhysiology::findOrFail($id);
        $leafPhysiology->update($request->all());


        return response()->json(['success' => 'Leaf Physiology updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $leafPhysiology = LeafPhysiology::findOrFail($id);
        // $leafPhysiology->delete();

        // return response()->json(['success' => 'Leaf Physiology deleted successfully.']);

        try {
            $leafPhysiology = LeafPhysiology::findOrFail($id);
            $leafPhysiology->delete();
    
            return response()->json(['success' => 'Leaf Physiology deleted successfully.']);
        } catch (\Exception $e) {
            \Log::error('Error deleting Leaf Physiology: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete Leaf Physiology.'], 500);
        }
    }

    

    public function edit(string $id)
    {
        $leaf = LeafPhysiology::find($id);

        if ($leaf) {
            return response()->json(['success' => true, 'data' => $leaf]);
        } else {
            return response()->json(['success' => false, 'message' => 'Leaf not found.'], 404);
        }
    }
}
