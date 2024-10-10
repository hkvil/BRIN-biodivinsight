<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObservationController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LeafPhysiologyController;
use App\Http\Controllers\SoilController;
use App\Http\Controllers\MicroclimateController;
use App\Http\Controllers\GreenHouseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageUserController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getActivityLogs'])->name('dashboard.data');

    Route::get('/users', [ManageUserController::class, 'index'])->name('users')->middleware('can:manage-users');
    Route::get('/users/data', [ManageUserController::class, 'getUsers'])->name('users.data')->middleware('can:manage-users');
    Route::delete('/users/destroy/{id}', [ManageUserController::class, 'destroy'])->name('users.destroy')->middleware('can:manage-users');
    Route::post('/users/store', [ManageUserController::class, 'store'])->name('users.store')->middleware('can:manage-users');
    Route::get('/users/edit/{id}', [ManageUserController::class, 'edit'])->name('users.edit')->middleware('can:manage-users');
    Route::put('/users/update/{id}', [ManageUserController::class, 'update'])->name('users.update')->middleware('can:manage-users');

    Route::get('/observations', [ObservationController::class, 'index'])->name('observations');
    Route::get('/observations/data', [ObservationController::class, 'getObservations'])->name('observations.data');
    Route::get('/observation/{id}', [ObservationController::class, 'detail'])->name('observation.detail');
    Route::put('/observations/update/{id}', [ObservationController::class, 'update'])->name('observations.update');
    Route::delete('/observations/destroy/{id}', [ObservationController::class, 'destroy'])->name('observations.destroy');
    Route::post('/observations/store', [ObservationController::class, 'store'])->name('observations.store');
    Route::get('/observation/edit/{id}', [ObservationController::class, 'edit'])->name('observation.edit');
    Route::post('/observations/{observationId}/add-user', [ObservationController::class, 'addUser'])->name('observation.addUser');
    Route::delete('/observations/{observation}/users/{user}', [ObservationController::class, 'detachUser'])->name('observation.detachUser');


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

    Route::get('/leafPhy/data', [LeafPhysiologyController::class, 'getLeafPhy'])->name('leafPhy.data');
    Route::delete('/leafPhy/{id}', [LeafPhysiologyController::class, 'destroy'])->name('leafPhy.destroy');
    Route::get('/leafPhy/edit/{id}', [LeafPhysiologyController::class, 'edit'])->name('leafPhy.edit');
    Route::put('/leafPhy/update/{id}', [LeafPhysiologyController::class, 'update'])->name('leafPhy.update');
    Route::post('/leafPhy/store', [LeafPhysiologyController::class, 'store'])->name('leafPhy.store');

    Route::resource('soil', SoilController::class);
    Route::resource('microclimate', MicroclimateController::class);

    Route::get('/gh/data', [GreenHouseController::class, 'getGreenHouseMeasurements'])->name('gh.data');
    Route::get('/gh/edit/{id}', [GreenHouseController::class, 'edit'])->name('gh.edit');
    Route::put('/gh/update/{id}', [GreenHouseController::class, 'update'])->name('gh.update');
    Route::delete('/gh/{id}', [GreenHouseController::class, 'destroy'])->name('gh.destroy');
    Route::post('/gh/store', [GreenHouseController::class, 'store'])->name('gh.store');

    
    Route::get('/api/plants', [ObservationController::class, 'getPlants'])->name('api.plants');
    Route::get('/api/locations', [ObservationController::class, 'getLocations'])->name('api.locations');
    Route::get('/api/observation-types', [ObservationController::class, 'getObservationType'])->name('api.observation-types');
    Route::get('/api/users', [ObservationController::class, 'getUsers'])->name('api.users');
});
    