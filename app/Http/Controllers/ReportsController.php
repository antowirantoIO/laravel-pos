<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\HPPProduct;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExpiredExport;
use App\Exports\LabaRugiExport;
use Illuminate\Support\Facades\View;
use PDF;

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

        if($type == "laba_rugi"){
            return Excel::download(new LabaRugiExport, 'laba_rugi-' . $month . '-' . $year . '.xlsx');
        }

        return redirect()->back();
    }

    public function calculate(Request $request)
    {

    $month = $request->bulan;
    $year = $request->tahun;

    $orders = Order::where('supplier_id', null)
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', date_parse($month)['month'])
        ->with(['items', 'payments', 'customer'])
        ->get();

    $penjualan = $orders->sum(function ($order) {
        return $order->total();
    });

    $potongan_penjualan = $orders->filter(function ($order) {
        return $order->isPaid();
    })->sum(function ($order) {
        return $order->total() - $order->receivedAmount();
    });

    $penjualan_bersih = $penjualan - $potongan_penjualan;

    $hpp_data = HPPProduct::where('tahun', $year)->where('bulan', date_parse($month)['month'])->get();
    $hpp = $hpp_data->sum(function ($hpp) {
        return $hpp->hpp;
    });

    $laba_kotor = $penjualan_bersih - $hpp;

    $data = [
        'penjualan' => $penjualan,
        'potongan_penjualan' => $potongan_penjualan,
        'penjualan_bersih' => $penjualan_bersih,
        'hpp' => $hpp,
        'laba_kotor' => $laba_kotor,
        'month' => $month,
        'year' => $year,
    ];

    $pdf = PDF::loadView('report.pdf', $data); // Load the view and pass data

    // view pdf in browser not download
    return $pdf->stream();
}

    public function hitungLabaKotor() {
        $orders = Order::where('supplier_id', null)
            ->with(['items', 'payments', 'customer'])
            ->get();
    
        $penjualan = $orders->filter(function ($order) {
            return $order->isPaid();
        })->sum(function ($order) {
            return $order->total();
        });
    
        $potongan_penjualan = $orders->filter(function ($order) {
            return $order->isPartialPaid();
        })->sum(function ($order) {
            return $order->total() - $order->receivedAmount();
        });
    
        $penjualan_bersih = $penjualan - $potongan_penjualan;
    
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

        dd($products);
    }
    
}
