<?php

namespace App\Http\Controllers;

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
            return Excel::download(new ProductExport, 'persediaan_barang' . $month . '-' . $year . '.xlsx');
        }

        if($type == 'barang_expired') {
            return Excel::download(new ProductExpiredExport, 'barang_expired' . $month . '-' . $year . '.xlsx');
        }

        return redirect()->back();
    }
}
