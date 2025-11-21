<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Item::with(['category', 'room', 'floor']);

        if (!empty($this->filters['item_name'])) {
            $query->where('name', 'like', '%' . $this->filters['item_name'] . '%');
        }

        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['room_id'])) {
            $query->where('room_id', $this->filters['room_id']);
        }

        if (!empty($this->filters['floor_id'])) {
            $query->where('floor_id', $this->filters['floor_id']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama',
            'Kategori',
            'Status',
            'Ruangan',
            'Lantai',
            'Tanggal Pasang',
            'Tanggal Maintenance',
        ];
    }

    public function map($item): array
    {
        return [
            $item->code,
            $item->name,
            $item->category->name,
            $item->status == 'active' ? 'Aktif' : 'Tidak Aktif',
            $item->room->name ?? '-',
            $item->floor->name ?? '-',
            $item->install_date,
            $item->replacement_date,
        ];
    }
}
