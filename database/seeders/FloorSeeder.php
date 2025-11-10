<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Floor;

class FloorSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 25; $i++) {
            Floor::create([
                'name' => 'Lantai ' . $i,
            ]);
        }
    }
}
