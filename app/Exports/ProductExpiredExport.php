<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductExpiredExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;
    
    public function query()
    {
        return Product::query()->where('quantity', '>', 0)->where('status', '=', 1)->where(
            'expired_date', '<', date('Y-m-d', strtotime('+1 month'))
        );
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product',
            'Barcode',
            'Expired Date',
            'Quantity',
            'Unit',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->barcode,
            $product->expired_date,
            $product->quantity,
            Product::UOM[$product->uom],
        ];
    }
}
