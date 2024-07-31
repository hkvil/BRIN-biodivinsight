<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Observation;
use App\Models\Plant;
use App\Models\LeafPhysiology;
use App\Models\Location;
use App\Models\Microclimate;
use App\Models\Soil;
use App\Models\Herbarium;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $plant = Plant::factory()->create([
            'id' => 1,
            'species_name' => 'Vigna unguiculata ',
            'common_name' => 'Kacang tunggak',
        ]);

        $location = Location::factory()->create([
            'id' => 1,
            'dusun' => 'Barong',
            'desa' => 'Barong',
            'kelurahan' => 'Galeh',
            'kecamatan' => 'Tangen',
            'kabupaten' => 'Sragen',
            'altitude' => '159,00m',
            'longitude' => '111.0922518',
            'latitude' => '-7.2647105',
        ]);

        $observation = Observation::factory()->create([
            'id' => 1,
            'plant_id' => 1,
            'location_id' => 1,
            'observation_date' => '2024-07-23',
            'observation_time' => '12:30:00',
        ]);
       
        // LeafPhysiology::factory()->create([
        //     'observation_id' => 1,
        //     'chlorophyll' => 28.3,
        //     'nitrogen' => 11.6,
        //     'leaf_moisture' => 46.4,
        //     'leaf_temperature' => 38.62,
        // ]);
        for ($i = 2; $i <= 11; $i++) {
            LeafPhysiology::factory()->create([
            'observation_id' => 1,
            'chlorophyll' => rand(200, 300) / 10,
            'nitrogen' => rand(100, 150) / 10,
            'leaf_moisture' => rand(400, 500) / 10,
            'leaf_temperature' => rand(350, 400) / 10,
            ]);
        }

        Microclimate::factory()->create([
            'observation_id' => 1,
            'temperature' => 33.6,
            'humidity' => 67,
            'pressure' => 14.67,
        ]);

        Soil::factory()->create([
            'observation_id' => $observation->id,
            'soil_moisture' => 50,
            'soil_temperature' => 20,
        ]);


    }
}
