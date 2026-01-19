<?php

namespace App\Exports;

use App\Models\DamageReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanKerusakanExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = DamageReport::with([
            'user',
            'item.category',
            'item.room',
            'item.floor',
            'item.building'
        ])->orderBy('created_at', 'desc');

        // 🔍 Nama Barang
        if (!empty($this->filters['item_name'])) {
            $query->whereHas('item', function ($q) {
                $q->where('name', 'like', '%' . $this->filters['item_name'] . '%');
            });
        }

        // 🔍 Kategori
        if (!empty($this->filters['category_id'])) {
            $query->whereHas('item', function ($q) {
                $q->where('category_id', $this->filters['category_id']);
            });
        }

        // 🔍 Status
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        // 🔍 Gedung
        if (!empty($this->filters['building_id'])) {
            $query->whereHas('item', function ($q) {
                $q->where('building_id', $this->filters['building_id']);
            });
        }

        // 🔍 Lantai
        if (!empty($this->filters['floor_id'])) {
            $query->whereHas('item', function ($q) {
                $q->where('floor_id', $this->filters['floor_id']);
            });
        }

        // 🔍 Ruangan
        if (!empty($this->filters['room_id'])) {
            $query->whereHas('item', function ($q) {
                $q->where('room_id', $this->filters['room_id']);
            });
        }

        // 🔍 Pelapor
        if (!empty($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }

        return $query->get()->values()->map(function ($report, $index) {
            return [
                'NO' => $index + 1,
                'Pelapor' => $report->user->name ?? '-',
                'Nama Barang' => $report->item->name ?? '-',
                'Kode Perangkat' => $report->item->code ?? '-',
                'Kategori' => $report->item->category->name ?? '-',
                'Lantai' => $report->item->floor->name ?? '-',
                'Ruangan' => $report->item->room->name ?? '-',
                'Deskripsi' => $report->reason,
                'Status' => ucfirst(str_replace('_', ' ', $report->status)),
                'Tanggal Laporan' => $report->created_at->format('d-m-Y H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'Pelapor',
            'Nama Barang',
            'Kode Perangkat',
            'Kategori',
            'Lantai',
            'Ruangan',
            'Deskripsi',
            'Status',
            'Tanggal Laporan',
        ];
    }
}
