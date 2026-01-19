<?php

namespace App\Exports;

use App\Models\DamageReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanKerusakanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DamageReport::with(['user', 'item.category', 'item.room', 'item.floor'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($report, $index) {
                return [
                    'NO' => $index + 1,
                    'Pelapor' => $report->user->name ?? '-',
                    'Nama Barang' => $report->item->name ?? '-',
                    'Kode Perangkat' => $report->item->code ?? '-',
                    'Kategori' => $report->item->category->name ?? '-',
                    'Lantai' => $report->item->floor->name ?? '-',
                    'Ruangan' => $report->item->room->name ?? '-',
                    'Deskripsi' => $report->reason,
                    'Status' => ucfirst($report->status),
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