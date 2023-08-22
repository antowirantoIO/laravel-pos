<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\HPPProduct;
use App\Models\UOM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = new Product();

        if ($request->search) {
            $products = $products->where('name', 'LIKE', "%{$request->search}%");
        }
        
        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', dateformat_custom());
        
        if (request()->wantsJson()) {
            return ProductResource::collection($products->where('expired_date', '>', $datetime) 
            ->latest()
            ->get());
        }
        
        return view('products.index')->with('products', $products->latest()
        ->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $uom = UOM::all();
        return view('products.create', compact('uom'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'barcode' => $request->barcode,
            'price' => $request->price,
            'purchase_price' => $request->purchase_price ?? 0,
            'expired_date' => $request->expired_date,
            'quantity' => $request->quantity,
            'status' => $request->status,
            'uom' => $request->uom,
            'created_at' => dateformat_custom(),
            'updated_at' => dateformat_custom(),
        ]);

        HPPProduct::create([
            'product_id' => $product->id,
            'hpp' => $request->purchase_price,
            'bulan' => date('m', strtotime(dateformat_custom())),
            'tahun' => date('Y', strtotime(dateformat_custom())),
        ]);

        if (!$product) {
            return redirect()->back()->with('error', 'Sorry, there a problem while creating product.');
        }
        return redirect()->route('products.index')->with('success', 'Success, you product have been created.');
    }

    public function updatePurchasePrice(Request $request){
        $product = Product::find($request->id);
        $product->purchase_price = $request->purchase_price;

        // HPPProduct::create([
        //     'product_id' => $product->id,
        //     'quantity' => $product->quantity,
        //     'price' => $request->purchase_price,
        //     'total' => $product->quantity * $request->purchase_price,
        //     'created_at' => dateformat_custom(),
        //     'updated_at' => dateformat_custom(),
        // ]);

        $product->save();
        return response()->json([
            'success' => true
        ]);
    }

    public function updatePurchaseExpiredDate(Request $request){
        $product = Product::find($request->id);
        $product->expired_date = $request->expired_date;
        $product->save();
        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $uom = UOM::all();
        return view('products.edit', compact('product', 'uom'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        // if(intval(str_replace(".00", "", $product->purchase_price)) != intval(str_replace(".00", "", $request->purchase_price))){
        //     HPPProduct::create([
        //         'product_id' => $product->id,
        //         'quantity' => $request->quantity,
        //         'price' => $request->purchase_price,
        //         'total' => $request->quantity * $request->purchase_price,
        //         'created_at' => dateformat_custom(),
        //         'updated_at' => dateformat_custom(),
        //     ]);
        // }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->barcode = $request->barcode;
        $product->price = $request->price;
        $product->purchase_price = $request->purchase_price;
        $product->expired_date = $request->expired_date;
        $product->quantity = $request->quantity;
        $product->uom = $request->uom;
        $product->status = $request->status;
        $product->updated_at = dateformat_custom();

        if (!$product->save()) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while updating product.');
        }
        return redirect()->route('products.index')->with('success', 'Success, your product have been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        DB::table('hpp_product')->where('product_id', $product->id)->delete();

        $product->delete();
        
        return response()->json([
            'success' => true
        ]);
    }
}
