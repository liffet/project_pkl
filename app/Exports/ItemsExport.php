<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Ambil data beserta relasinya
        return Item::with(['category', 'room', 'floor'])->get();
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->code,
            $item->name,
            $item->category->name ?? '-',
            $item->room->name ?? '-',
            $item->floor->name ?? '-',
            $item->status,
            $item->install_date,
            $item->replacement_date,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode Barang',
            'Nama Barang',
            'Kategori',
            'Ruangan',
            'Lantai',
            'Status',
            'Tanggal Pemasangan',
            'Tanggal Penggantian',
        ];
    }
}
