<?php

namespace App\Exports;

use App\Models\Room;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RoomsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Room::with('floor')->withCount('items')->get();
    }

    public function map($room): array
    {
        return [
            $room->id,
            $room->name,
            $room->floor->name ?? '-',
            $room->items_count,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Ruangan',
            'Lantai',
            'Jumlah Barang',
        ];
    }
}
