<?php

namespace App\Exports;

use App\Models\Product;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Import ShouldAutoSize interface
use Maatwebsite\Excel\Concerns\WithColumnFormatting; // Import WithColumnFormatting interface
use PhpOffice\PhpSpreadsheet\Style\NumberFormat; // Import NumberFormat class

class ProductExpiredExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    use Exportable;

    public function query()
    {
        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', dateformat_custom());
        $prod = Product::query()->where('quantity', '>', 0)->where('status', '=', 1)
        ->where(
            'expired_date', '<', $datetime->addDays(15)->format('Y-m-d H:i:s')
        );

        return $prod;
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
            $product->uom_prod->name,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY, // Format for 'Expired Date' column as date
        ];
    }
}
