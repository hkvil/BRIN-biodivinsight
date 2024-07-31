<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObservationController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\LocationController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/observations', [ObservationController::class, 'index'])->name('observations');
    Route::get('/observations/data', [ObservationController::class, 'getObservations'])->name('observations.data');
    Route::get('/observation/{id}', [ObservationController::class, 'detail'])->name('observation.detail');

    Route::get('/plants', [PlantController::class, 'index'])->name('plants');
    Route::get('/plants/data', [PlantController::class, 'getPlants'])->name('plants.data');
    Route::post('/plants/store', [PlantController::class, 'store'])->name('plants.store');
    Route::get('/plants/edit/{id}', [PlantController::class, 'edit'])->name('plants.edit');
    Route::put('/plants/update/{id}', [PlantController::class, 'update'])->name('plants.update');
    Route::delete('/plants/destroy/{id}', [PlantController::class, 'destroy'])->name('plants.destroy');

    Route::get('/locations', [LocationController::class, 'index'])->name('locations');
    Route::get('/locations/data', [LocationController::class, 'getLocations'])->name('locations.data');
    Route::post('/locations/store', [LocationController::class, 'store'])->name('locations.store');
    Route::get('/locations/edit/{id}', [LocationController::class, 'edit'])->name('locations.edit');
    Route::put('/locations/update/{id}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/destroy/{id}', [LocationController::class, 'destroy'])->name('locations.destroy');


});
