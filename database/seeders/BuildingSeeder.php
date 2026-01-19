<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run()
    {
        $buildings = [
            ['name' => 'Karya', 'total_floors' => 25],
            ['name' => 'Karsa', 'total_floors' => 9],
            ['name' => 'Cipta', 'total_floors' => 7],
            ['name' => 'Merdeka Timur', 'total_floors' => 4],
        ];

        foreach ($buildings as $b) {
            Building::create($b);
        }
    }
}
    