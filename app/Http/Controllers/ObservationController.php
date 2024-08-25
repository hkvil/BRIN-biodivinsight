<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Observation; 
use App\Models\Plant;
use App\Models\Location;
use App\Models\Remark;
use App\Models\LeafPhysiology;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ObservationController extends Controller
{
    public function index()
    {
        return view('observations');
    }

    public function detail($id)
    {
        $observation = Observation::find($id);
        $can_modify = Auth::user()->can('delete', $observation);

        if($observation->observation_type == Observation::TYPE_LAB){
            
            return view('observation-detail', [
                'observation' => $observation,
                'users' => $observation->users,
                'can_modify' => $can_modify,
                'leafPhy' => null,
            ]);
        }
        
        $soil = $observation->soil;
        $microclimate = $observation->microclimate;
        $leafPhysiology = $observation->leafPhysiology;
        
        return view('observation-detail', [
        'observation' => $observation,
        'users' => $observation->users,
        'soil' => $soil, 
        'microclimate' => $microclimate,
        'leafPhy' => $leafPhysiology,
        'can_modify' => $can_modify
    ]);
    }

    public function getObservations(Request $request)
    {
        if ($request->ajax()) {
            
            $observations = Observation::with('plant', 'location', 'remarks')
            ->get();

            return datatables()->of($observations)
                ->addColumn('plant_name', function($row){
                    return $row->plant->species_name;
                })
                ->addColumn('location_name', function($row){
                    return $row->location->desa;
                })->addColumn('remarks', function($row){
                    return $row->remarks->remarks;
                })->addColumn('can_modify', function($row) {
                    return Auth::user()->can('delete', $row);
                })
                ->make(true);
                
        }
    }

    public function update(Request $request, string $id)
    {
        $observation = Observation::find($id);
        if (Auth::user()->cannot('update', $observation)) {
            abort(403);
        }
        $observation->observation_type = $request->input('observation_type');
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
        if (Auth::user()->cannot('delete', $observation)) {
            abort(403);
        }

        if($observation->observation_type == Observation::TYPE_LAB){
            $observation->greenHouseMeasurement()->delete();
            $observation->delete();
            return response()->json(['success'=>'Observation deleted successfully.']);
        }
        $observation->leafPhysiology()->delete();
        $observation->microclimate()->delete();
        $observation->soil()->delete();
        $observation->delete();
        return response()->json(['success'=>'Observation deleted successfully.','leafphysiology'=>$observation->leafPhysiology()->exists()]);
    }

    public function edit(string $id)
    {
        $observation = Observation::find($id);
        if (Auth::user()->cannot('update', $observation)) {
            abort(403);
        }
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
        $userId = Auth::id();

        $request->validate([
            'observation_type' => 'required',
            'plant_id' => 'required',
            'location_id' => 'required',
            'observation_date' => 'required',
            'observation_time' => 'required',
            'remarks' => 'nullable|string'
        ]);

        $observation = Observation::create([
            'user_id' => $userId,
            'observation_type' => $request->observation_type,
            'plant_id' => $request->plant_id,
            'location_id' => $request->location_id,
            'observation_date' => $request->observation_date,
            'observation_time' => $request->observation_time,
        ]);

        $observation->users()->attach($userId, ['is_owner' => true]);

        if ($request->has('remarks') && !empty($request->remarks)) {
            $remark = Remark::create([
                'observation_id' => $observation->id,
                'remarks' => $request->remarks,
            ]);
            $observation->update(['remark_id' => $remark->id]);
        }

        return response()->json(['success' => 'Observation created successfully.']);
    }

    public function addUser(Request $request, $observationId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->input('user_id');

        // Find the observation by ID
        $observation = Observation::findOrFail($observationId);

        // Attach the user to the observation
        $observation->users()->attach($userId);

        // Optionally return a response or redirect
        return redirect()->back()->with('success', 'User added successfully.');
    }

    public function detachUser($observationId, $userId)
    {
        $observation = Observation::findOrFail($observationId);
        $user = User::findOrFail($userId);

        // Check if the current user can modify this observation
        if (Auth::user()->cannot('delete', $observation)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Detach the user from the observation
        $observation->users()->detach($userId);

        return response()->json(['success' => 'User removed successfully.']);
    }

        // Method for Select2 library
        public function getPlantsS2()
        {
            $plants = Plant::all(['id', 'species_name','common_name']);
            return response()->json($plants);
        }

        public function getObservationTypeS2(){
            $observationTypes = [Observation::TYPE_LAB, Observation::TYPE_FIELD];
            return response()->json($observationTypes);
        }

        // public function getUsers()
        // {
        //     $users = User::all(['id', 'name', 'email']); 
    
        //     return response()->json($users);
        // }

        public function getUsers(Request $request)
        {
            $observationId = $request->input('observation_id');
            // $observationId = 2;
            // Retrieve all users
            $users = User::query()
                // Exclude users already associated with the given observation
                ->whereDoesntHave('observations', function ($query) use ($observationId) {
                    $query->where('observation_id', $observationId);
                })
                ->get(['id', 'name', 'email']);
            
            return response()->json($users);
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
