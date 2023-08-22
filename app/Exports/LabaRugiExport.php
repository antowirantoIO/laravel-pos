<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class LabaRugiExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;
    
    public function collection()
    {
        $products = Product::all();

        foreach ($products as $product) {
            $persediaan_awal = $product->hpp()->first()->total / $product->hpp()->first()->quantity;
            $product->persediaan_awal = $persediaan_awal;

            $persediaan_akhir = $product->hpp()->latest()->take(2)->get();
            $jumlah = $persediaan_akhir->sum('total');
            $jumlah_qty = $persediaan_akhir->sum('quantity');
            $product->persediaan_akhir = $jumlah_qty !== 0 ? $jumlah / $jumlah_qty : 0;

            $penjualan_bersih_qty = $product->orderItems->sum('quantity');
            $penjualan_bersih_harga = $penjualan_bersih_qty * $product->price;

            $persediaan_akhir_harga = $product->quantity * $product->price;

            $hpp = $penjualan_bersih_harga / $persediaan_akhir_harga;

            $product->hpp = $hpp;
        }

        return $products;
    }

    public function headings(): array {
        return [
            'Nama Produk',
            'Harga Jual',
            'Harga Beli',
            'QTY',
            'Persediaan Awal',
            'Persediaan Akhir',
            'HPP'
        ];
    }

    public function map($product): array {
        return [
            $product->name,
            $product->price,
            $product->purchase_price,
            $product->quantity,
            $product->persediaan_awal,
            $product->persediaan_akhir,
            $product->hpp
        ];
    }
}
