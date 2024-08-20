<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GreenHouseMeasurement;


class GreenHouseController extends Controller
{
    public function getGreenHouseMeasurements(Request $request)
    {
        if ($request->ajax()) {
            $observationId = $request->input('observation_id');

            $gh = GreenHouseMeasurement::where('observation_id', $observationId)
                        ->get();
            return datatables()->of($gh)->make(true);
        }
        // select('id', 'chlorophyll', 'nitrogen', 'leaf_moisture', 'leaf_temperature')

    }

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
    public function store(Request $request)
    {
        $observationId = $request->input('observation_id');

        $request->validate([
            'no' => 'required',
            'kode' => 'required',
            'minggu_panen' => 'required',
            'perlakuan_penyiraman' => 'required',
            'tinggi_tanaman' => 'required',
            'mean_tinggi_tanaman' => 'nullable',
            'stddev_tinggi_tanaman' => 'nullable',
            'panjang_akar' => 'required',
            'mean_panjang_akar' => 'nullable',
            'stddev_panjang_akar' => 'nullable',
            'bb_tunas' => 'required',
            'mean_bb_tunas' => 'nullable',
            'stddev_bb_tunas' => 'nullable',
            'bk_tunas' => 'required',
            'mean_bk_tunas' => 'nullable',
            'stddev_bk_tunas' => 'nullable',
            'bb_akar' => 'required',
            'mean_bb_akar' => 'nullable',
            'stddev_bb_akar' => 'nullable',
            'bk_akar' => 'required',
            'mean_bk_akar' => 'nullable',
            'stddev_bk_akar' => 'nullable',
        ]);
    

        GreenHouseMeasurement::create([
            'observation_id' => $observationId,
            'chlorophyll' => $request->chlorophyll,
            'nitrogen' => $request->nitrogen,
            'leaf_moisture' => $request->leaf_moisture,
            'leaf_temperature' => $request->leaf_temperature,
            'no' => $request->no,
            'kode' => $request->kode,
            'minggu_panen' => $request->minggu_panen,
            'perlakuan_penyiraman' => $request->perlakuan_penyiraman,
            'tinggi_tanaman' => $request->tinggi_tanaman,
            'mean_tinggi_tanaman' => $request->mean_tinggi_tanaman,
            'stddev_tinggi_tanaman' => $request->stddev_tinggi_tanaman,
            'panjang_akar' => $request->panjang_akar,
            'mean_panjang_akar' => $request->mean_panjang_akar,
            'stddev_panjang_akar' => $request->stddev_panjang_akar,
            'bb_tunas' => $request->bb_tunas,
            'mean_bb_tunas' => $request->mean_bb_tunas,
            'stddev_bb_tunas' => $request->stddev_bb_tunas,
            'bk_tunas' => $request->bk_tunas,
            'mean_bk_tunas' => $request->mean_bk_tunas,
            'stddev_bk_tunas' => $request->stddev_bk_tunas,
            'bb_akar' => $request->bb_akar,
            'mean_bb_akar' => $request->mean_bb_akar,
            'stddev_bb_akar' => $request->stddev_bb_akar,
            'bk_akar' => $request->bk_akar,
            'mean_bk_akar' => $request->mean_bk_akar,
            'stddev_bk_akar' => $request->stddev_bk_akar,
        ]);

        return response()->json(['success' => 'Green House Data created successfully.']);
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
        $gh = GreenHouseMeasurement::find($id);

        if ($gh) {
            return response()->json(['success' => true, 'data' => $gh]);
        } else {
            return response()->json(['success' => false, 'message' => 'Green House data not found.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'no' => 'required',
            'kode' => 'required',
            'minggu_panen' => 'required',
            'perlakuan_penyiraman' => 'required',
            'tinggi_tanaman' => 'required',
            'mean_tinggi_tanaman' => 'nullable',
            'stddev_tinggi_tanaman' => 'nullable',
            'panjang_akar' => 'required',
            'mean_panjang_akar' => 'nullable',
            'stddev_panjang_akar' => 'nullable',
            'bb_tunas' => 'required',
            'mean_bb_tunas' => 'nullable',
            'stddev_bb_tunas' => 'nullable',
            'bk_tunas' => 'required',
            'mean_bk_tunas' => 'nullable',
            'stddev_bk_tunas' => 'nullable',
            'bb_akar' => 'required',
            'mean_bb_akar' => 'nullable',
            'stddev_bb_akar' => 'nullable',
            'bk_akar' => 'required',
            'mean_bk_akar' => 'nullable',
            'stddev_bk_akar' => 'nullable',
        ]);

        $gh = GreenHouseMeasurement::findOrFail($id);
        $gh->update($request->all());


        return response()->json(['success' => 'Green House data updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $gh = GreenHouseMeasurement::findOrFail($id);
            $gh->delete();
    
            return response()->json(['success' => 'Green House data deleted successfully.']);
        } catch (\Exception $e) {
            \Log::error('Error deleting Green House data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete Green House data.'], 500);
        }
    }
}
