<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return response(
                $request->user()->purchase()->get()
            );
        }
        return view('purchase.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|exists:products,barcode',
        ]);
        $barcode = $request->barcode;

        $product = Product::where('barcode', $barcode)->first();
        $purchase = $request->user()->purchase()->where('barcode', $barcode)->first();

        if ($purchase) {
            $purchase->pivot->quantity = $purchase->pivot->quantity + 1;
            $purchase->pivot->save();
        } else {
            $request->user()->purchase()->attach($product->id, ['quantity' => 1]);
        }

        return response('', 204);
    }

    public function changeQty(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $purchase = $request->user()->purchase()->where('id', $request->product_id)->first();

        if ($purchase) {
            $purchase->pivot->quantity = $request->quantity;
            $purchase->pivot->save();
        }

        return response([
            'success' => true
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);
        $request->user()->purchase()->detach($request->product_id);

        return response('', 204);
    }

    public function empty(Request $request)
    {
        $request->user()->purchase()->detach();

        return response('', 204);
    }
}
