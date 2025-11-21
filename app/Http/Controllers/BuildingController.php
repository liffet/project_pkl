<?php

namespace App\Http\Controllers;

use App\Models\Building;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::with('floors')->get();
        return view('building.index', compact('buildings'));
    }
}
