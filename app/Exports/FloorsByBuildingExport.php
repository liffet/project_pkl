<?php

namespace App\Exports;

use App\Models\Floor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FloorsByBuildingExport implements FromCollection, WithHeadings
{
    protected $building_id;

    public function __construct($building_id)
    {
        $this->building_id = $building_id;
    }

    public function collection()
    {
        return Floor::where('building_id', $this->building_id)
            ->select('id', 'name', 'created_at')
            ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Nama Lantai', 'Dibuat Pada'];
    }
}
