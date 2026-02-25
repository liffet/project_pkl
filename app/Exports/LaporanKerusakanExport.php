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

        // ================= FILTER =================

        if (!empty($this->filters['item_name'])) {
            $query->whereHas('item', function ($q) {
                $q->where('name', 'like', '%' . $this->filters['item_name'] . '%');
            });
        }

        if (!empty($this->filters['category_id'])) {
            $query->whereHas('item', function ($q) {
                $q->where('category_id', $this->filters['category_id']);
            });
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['building_id'])) {
            $query->whereHas('item', function ($q) {
                $q->where('building_id', $this->filters['building_id']);
            });
        }

        if (!empty($this->filters['floor_id'])) {
            $query->whereHas('item', function ($q) {
                $q->where('floor_id', $this->filters['floor_id']);
            });
        }

        if (!empty($this->filters['room_id'])) {
            $query->whereHas('item', function ($q) {
                $q->where('room_id', $this->filters['room_id']);
            });
        }

        if (!empty($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }

        // ================= FILTER TANGGAL =================

        // Filter 1 hari
        if (!empty($this->filters['tanggal'])) {
            $query->whereDate('created_at', $this->filters['tanggal']);
        }

        // Filter periode
        if (!empty($this->filters['tanggal_dari']) && !empty($this->filters['tanggal_sampai'])) {
            $query->whereBetween('created_at', [
                $this->filters['tanggal_dari'] . ' 00:00:00',
                $this->filters['tanggal_sampai'] . ' 23:59:59'
            ]);
        }

        // ================= MAP DATA =================

        return $query->get()->values()->map(function ($report, $index) {
            return [
                'NO' => $index + 1,
                'Pelapor' => $report->user->name ?? '-',
                'Nama Barang' => $report->item->name ?? '-',
                'Kode Perangkat' => $report->item->code ?? '-',
                'Kategori' => $report->item->category->name ?? '-',
                'Gedung' => $report->item->building->name ?? '-',
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
            'Gedung',
            'Lantai',
            'Ruangan',
            'Deskripsi',
            'Status',
            'Tanggal Laporan',
        ];
    }
}