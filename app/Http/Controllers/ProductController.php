<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $products = $products->latest()->get();
        if (request()->wantsJson()) {
            return ProductResource::collection($products);
        }
        return view('products.index')->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        /*$image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('products', 'public');
        }*/

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
        ]);

        if (!$product) {
            return redirect()->back()->with('error', 'Sorry, there a problem while creating product.');
        }
        return redirect()->route('products.index')->with('success', 'Success, you product have been created.');
    }

    public function updatePurchasePrice(Request $request){
        $product = Product::find($request->id);
        $product->purchase_price = $request->purchase_price;
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
        return view('products.edit')->with('product', $product);
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
        $product->name = $request->name;
        $product->description = $request->description;
        $product->barcode = $request->barcode;
        $product->price = $request->price;
        $product->purchase_price = $request->purchase_price;
        $product->expired_date = $request->expired_date;
        $product->quantity = $request->quantity;
        $product->uom = $request->uom;
        $product->status = $request->status;

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
        $product->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
