<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExpiredExport;

class ReportsController extends Controller
{
    public function index() {
        $months = [];
        for ($i = 1; $i <= date('m'); $i++) {
            $months[$i] = date('F', mktime(0, 0, 0, $i, 1));
        }
        $year = date('Y') - 5;
        $years = [];
        for ($i = 0; $i <= 5; $i++) {
            $years[$year + $i] = $year + $i;
        }
        $years = array_reverse($years);
        $months = array_reverse($months);

        $selected_month = strtolower(date('F'));
        $selected_year = date('Y');

        return view('reports.index', compact('months', 'years', 'selected_month', 'selected_year'));
    }

    public function download(Request $request){
        $month = $request->bulan;
        $year = $request->tahun;
        $type = $request->jenis_laporan;

        if($type == 'persediaan_barang') {
            return Excel::download(new ProductExport, 'persediaan_barang-' . $month . '-' . $year . '.xlsx');
        }

        if($type == 'barang_expired') {
            return Excel::download(new ProductExpiredExport, 'barang_expired-' . $month . '-' . $year . '.xlsx');
        }

        return redirect()->back();
    }

    public function hitungLabaKotor(){
        $orders = new Order();
        $orders = $orders->where('supplier_id', '=', null)->with(['items', 'payments', 'customer'])->get();
        $penjualan = $orders->filter(function($i) {
            return $i->isPaid();
        })->map(function($i) {
            return $i->total();
        })->sum();

        $potongan_penjualan = $orders->filter(function($i) {
            return $i->isPartialPaid();
        })->map(function($i) {
            return $i->total() - $i->receivedAmount();
        })->sum();

        $penjualan_bersih = $penjualan - $potongan_penjualan;

        $product = Product::all();

        foreach($product as $p) {
            $persediaan_awal = $p->hpp()->get()->take(1)->map(function($i) {
                return $i->total / $i->quantity;
            })->sum();

            $product->persediaan_awal = $persediaan_awal;

            $persediaan_akhir = $p->hpp()->get()->take(2);

            $jumlah = $persediaan_akhir->map(function($i) {
                return $i->total;
            })->sum();

            $jumlah_qty = $persediaan_akhir->map(function($i) {
                return $i->quantity;
            })->sum();

            $product->persediaan_akhir = $jumlah / $jumlah_qty;

            echo $product->persediaan_akhir . "<=AKHIR AWAL=>" . $product->persediaan_awal . "<br>";

            $penjualan_bersih_qty = $p->orderItems()->get()->map(function($i) {
                return $i->quantity;
            })->sum();

            $penjualan_bersih_harga = $penjualan_bersih_qty * $p->price;

            echo $penjualan_bersih_harga . "<=HARGA ORDER ITEMS=>" . $penjualan_bersih_qty . "<br>";

            $persediaan_akhir_harga = $p->quantity * $p->price;

            echo $persediaan_akhir_harga . "<=HARGA PERS. AKHIR=>" . $p->quantity . "<br>";

            $hpp = $penjualan_bersih_harga / $persediaan_akhir_harga;

            echo $hpp . "<=HPP=>" . $hpp . "<br>";
        }

    }
}
