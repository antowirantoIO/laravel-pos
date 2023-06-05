<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function laba_kotor() {
        return view('reports.laba_kotor');
    }
}
