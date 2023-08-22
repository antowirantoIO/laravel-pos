<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UOM;

class UOMController extends Controller
{
    public function index(Request $request)
    {
        $uoms = new UOM();
        if ($request->search) {
            $uoms = $uoms->where('name', 'LIKE', "%{$request->search}%");
        }
        $uoms = $uoms->latest()->get();
        return view('uoms.index')->with('uoms', $uoms);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:uoms'
        ]);
        
        // if request failed validation, it will redirect back to create page
        // with error message

        if($request->fails()) {
            return redirect()->back()->with('error', 'UOM gagal ditambahkan');
        }

        $uom = UOM::create([
            'name' => $request->name
        ]);

        return redirect()->route('uom.index')->with('success', 'UOM berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $uom = UOM::find($id);
        $uom->update([
            'name' => $request->name
        ]);

        return redirect()->route('uom.index')->with('success', 'UOM berhasil diupdate');
    }

    public function destroy(UOM $uom)
    {
        $uom->delete();

        return redirect()->route('uom.index')->with('success', 'UOM berhasil dihapus');
    }
}
