<?php

namespace App\Exports;

use App\Models\Floor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FloorsExport implements FromCollection, WithHeadings, WithMapping
{

    public function collection()
    {
        return Floor::with('rooms')->get();
    }


    public function map($floor): array
    {
        return [
            $floor->id,
            $floor->name,
            $floor->rooms->count() . ' Room(s)',
            $floor->items->count() . ' Item(s)',
        ];
    }

    
    public function headings(): array
    {
        return [
            'ID',
            'Nama Lantai',
            'Jumlah Room',
            'Jumlah Item',
        ];
    }
}
