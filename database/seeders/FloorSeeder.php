<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;
use App\Models\Floor;

class FloorSeeder extends Seeder
{
    public function run()
    {
        $buildings = Building::all();

        foreach ($buildings as $building) {
            for ($i = 1; $i <= $building->total_floors; $i++) {
                Floor::create([
                    'building_id' => $building->id,
                    'name' => 'Lantai ' . $i,
                ]);
            }   
        }
    }
}
