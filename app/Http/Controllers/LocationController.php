<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        return view('locations');
    }

    public function getLocations(Request $request)
    {
        if ($request->ajax()) {
            $locations = Location::select(
            'id',
            'dusun',
            'desa',
            'kelurahan',
            'kecamatan',
            'kabupaten',
            'altitude',
            'longitude',
            'latitude')->get();
            return datatables()->of($locations)->make(true);
        }
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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'dusun' => 'required|string|max:15',
            'desa' => 'required|string|max:15',
            'kelurahan' => 'required|string|max:15',
            'kecamatan' => 'required|string|max:15',
            'kabupaten' => 'required|string|max:15',
            'altitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
        ]);

        try {
            // Membuat instance Location baru
            $location = new Location;
            $location->dusun = $validatedData['dusun'];
            $location->desa = $validatedData['desa'];
            $location->kelurahan = $validatedData['kelurahan'];
            $location->kecamatan = $validatedData['kecamatan'];
            $location->kabupaten = $validatedData['kabupaten'];
            $location->altitude = $validatedData['altitude'];
            $location->longitude = $validatedData['longitude']; // Perbaikan penamaan
            $location->latitude = $validatedData['latitude'];
    
            // Menyimpan data ke database
            $location->save();
    
            return response()->json(['success' => 'Location created successfully.', 'dusun' => $location->dusun], 201);
    
        } catch (\Exception $e) {
            // Penanganan kesalahan
            return response()->json(['error' => 'Failed to create location.'], 500);
        }
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
        try {
            $location = Location::findOrFail($id);
            return response()->json(['success' => true, 'data' => $location], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Location not found.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'dusun' => 'required|string|max:15',
            'desa' => 'required|string|max:15',
            'kelurahan' => 'required|string|max:15',
            'kecamatan' => 'required|string|max:15',
            'kabupaten' => 'required|string|max:15',
            'altitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
        ]);

        try {
            // Mencari lokasi berdasarkan ID
            $location = Location::findOrFail($id);

            // Mengupdate data lokasi
            $location->dusun = $validatedData['dusun'];
            $location->desa = $validatedData['desa'];
            $location->kelurahan = $validatedData['kelurahan'];
            $location->kecamatan = $validatedData['kecamatan'];
            $location->kabupaten = $validatedData['kabupaten'];
            $location->altitude = $validatedData['altitude'];
            $location->longitude = $validatedData['longitude'];
            $location->latitude = $validatedData['latitude'];

            // Menyimpan perubahan ke database
            $location->save();

            return response()->json(['success' => 'Location updated successfully.', 'dusunName' => $location->dusun], 200);

        } catch (\Exception $e) {
            // Penanganan kesalahan
            return response()->json(['error' => 'Failed to update location.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Mencari lokasi berdasarkan ID
            $location = Location::findOrFail($id);
    
            // Menghapus lokasi
            $location->delete();
    
            // Mengembalikan respon sukses
            return response()->json(['success' => true, 'message' => 'Location deleted successfully.'], 200);
        } catch (\Exception $e) {
            // Penanganan kesalahan jika lokasi tidak ditemukan
            return response()->json(['success' => false, 'message' => 'Location not found.'], 404);
        }
    }
}
