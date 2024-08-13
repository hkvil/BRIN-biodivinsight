<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Observation; 
use App\Models\Soil;
use App\Models\Plant;
use App\Models\Location;
use App\Models\Remark;
use App\Models\LeafPhysiology;

class ObservationController extends Controller
{
    public function index()
    {
        return view('observations');
    }

    public function detail($id)
    {
        $observation = Observation::find($id);
        $soil = $observation->soil;
        $microclimate = $observation->microclimate;
        $leafPhysiology = $observation->leafPhysiology;
        
        return view('observation-detail', [
        'observation' => $observation,
        'soil' => $soil, 
        'microclimate' => $microclimate,
        'leafPhy' => $leafPhysiology
    ]);
    }

    public function getObservations(Request $request)
    {
        if ($request->ajax()) {
            $observations = Observation::with('plant', 'location','remarks')->get();
            return datatables()->of($observations)
                ->addColumn('plant_name', function($row){
                    return $row->plant->species_name;
                })
                ->addColumn('location_name', function($row){
                    return $row->location->desa;
                })->addColumn('remarks', function($row){
                    return $row->remarks->remarks;
                })->make(true);
                
        }
    }

    public function update(Request $request, string $id)
    {
        $observation = Observation::find($id);
        $observation->plant_id = $request->input('plant_id');
        $observation->location_id = $request->input('location_id');
        $observation->observation_date = $request->input('observation_date');
        $observation->observation_time = $request->input('observation_time');
        if ($observation->remarks) {
            $observation->remarks->remarks = $request->input('remarks');
            $observation->remarks->save();
        }
        $observation->save();
        return response()->json(['success'=>'Observation updated successfully.']);
    }

    public function destroy(string $id)
    {
        $observation = Observation::find($id);
        #Perlu pemikiran lebih lanjut
        $observation->leafPhysiology()->delete();
        $observation->microclimate()->delete();
        $observation->soil()->delete();
        $observation->delete();
        return response()->json(['success'=>'Observation deleted successfully.','leafphysiology'=>$observation->leafPhysiology()->exists()]);
    }

    public function edit(string $id)
    {
        $observation = Observation::find($id);
        $plants = Plant::all();
        $locations = Location::all();
        $remarks = $observation->remarks;

        if ($observation) {
            return response()->json(['success' => true, 'data' => $observation, 'plants' => $plants, 'locations' => $locations, 'remarks' => $remarks]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function store (Request $request)
    {
        $request->validate([
            'plant_id' => 'required',
            'location_id' => 'required',
            'observation_date' => 'required',
            'observation_time' => 'required',
            'remarks' => 'nullable|string'
        ]);

        $observation = Observation::create([
            'plant_id' => $request->plant_id,
            'location_id' => $request->location_id,
            'observation_date' => $request->observation_date,
            'observation_time' => $request->observation_time,
        ]);

        if ($request->has('remarks') && !empty($request->remarks)) {
            $remark = Remark::create([
                'observation_id' => $observation->id,
                'remarks' => $request->remarks,
            ]);
            $observation->update(['remark_id' => $remark->id]);
        }

        return response()->json(['success' => 'Observation created successfully.']);
    }

        // Method for Select2 library
        public function getPlantsS2()
        {
            $plants = Plant::all(['id', 'species_name','common_name']);
            return response()->json($plants);
        }
    
        public function getLocationsS2()
        {
            $locations = Location::all([
                'id',
                'dusun',
                'desa',
                'kelurahan',
                'kecamatan',
                'kabupaten',
                'altitude',
                'longitude',
                'latitude'
            ]);
            return response()->json($locations);
        }
}
