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
use App\Models\Remark;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
        ]);
        

        Plant::create([
            'id' => 1,
            'species_name' => 'Vigna unguiculata ',
            'common_name' => 'Kacang tunggak',
        ]);

        Location::create([
            'id' => 1,
            'dusun' => 'Barong',
            'desa' => 'Barong',
            'kelurahan' => 'Galeh',
            'kecamatan' => 'Tangen',
            'kabupaten' => 'Sragen',
            'altitude' => 2500,
            'longitude' => 111.0922518,
            'latitude' => -7.2647105,
        ]);

        Observation::create([
            'id' => 1,
            'plant_id' => 1,
            'location_id' => 1,
            'remark_id' => 1,
            'observation_type' => 'Field Observation',
            'observation_date' => '2024-07-23',
            'observation_time' => '12:30:00',
        ]);

        Remark::create([
            'observation_id' => 1,
            'remarks' => 'Hari ini cuaca cerah',
        ]);
           
        // LeafPhysiology::create([
        //     'observation_id' => 1,
        //     'chlorophyll' => 28.3,
        //     'nitrogen' => 11.6,
        //     'leaf_moisture' => 46.4,
        //     'leaf_temperature' => 38.62,
        // ]);
        for ($i = 2; $i <= 11; $i++) {
            LeafPhysiology::create([
            'observation_id' => 1,
            'chlorophyll' => rand(200, 300) / 10,
            'nitrogen' => rand(100, 150) / 10,
            'leaf_moisture' => rand(400, 500) / 10,
            'leaf_temperature' => rand(350, 400) / 10,
            ]);
        }

        Microclimate::create([
            'observation_id' => 1,
            'temperature' => 33.6,
            'humidity' => 67,
            'pressure' => 14.67,
        ]);

        Soil::create([
            'observation_id' => 1,
            'ph' => 6.5,
            'moisture' => 36.5,
            'temperature' => 27.5,
        ]);

    }
}
