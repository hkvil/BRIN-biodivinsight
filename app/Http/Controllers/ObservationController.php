<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Observation; 
use App\Models\Plant;
use App\Models\Location;

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
            $observations = Observation::with('plant', 'location')->get();
            return datatables()->of($observations)
                ->addColumn('plant_name', function($row){
                    return $row->plant->species_name;
                })
                ->addColumn('location_name', function($row){
                    return $row->location->desa;
                })
                ->make(true);
        }
    }
}
