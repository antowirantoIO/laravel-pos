<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet; // Import AfterSheet class
use PhpOffice\PhpSpreadsheet\Style\NumberFormat; // Import NumberFormat class
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ProductExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    public function query()
    {
        return Product::query()->where('quantity', '>', 0)->where('status', '=', 1);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product',
            'Barcode',
            'Harga Jual',
            'Harga Beli',
            'Quantity',
            'Unit',
        ];
    }

    public function map($product): array
    {
    
        return [
            $product->id,
            $product->name,
            intval($product->barcode),
            "Rp." . number_format($product->price),
            "Rp." . number_format($product->purchase_price),
            $product->quantity,
            $product->uom_prod->name,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                foreach ($sheet->getColumnIterator('C') as $column) {
                    foreach ($column->getCellIterator() as $cell) {
                        $cell->setValueExplicit($cell->getValue(), DataType::TYPE_NUMERIC);
                    }
                }
            },
        ];
    }

}
