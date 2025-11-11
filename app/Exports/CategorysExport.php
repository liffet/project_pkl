<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategorysExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Category::withCount('items')->get();
    }

    public function map($category): array
    {
        return [
            $category->id,
            $category->name,
            $category->items_count,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kategori',
            'Jumlah Barang',
        ];
    }
}
