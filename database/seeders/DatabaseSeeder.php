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
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);
        
    }
}
