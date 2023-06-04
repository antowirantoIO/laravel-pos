<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierStoreRequest;
use App\Models\Supplier;
use Storage;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
		return view('suppliers.index')->with('suppliers', $suppliers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierStoreRequest $request)
    {
        /*$avatar_path = '';
		if ($request->hasFile('avatar')){
			$avatar_path = $request->file('avatar')->store('suppliers');
		}*/
        $supplier = Supplier::create([
		'supplier_name' => $request->supplier_name,
		'address'  => $request->address,
		'phone'  => $request->phone,
		//'avatar'  => $avatar_path,
		'user_id'  => $request->user()->id,
	] );
		
		if (! $supplier) {
			return redirect()->back()->with('error', 'Sorry, there a problem while creating supplier');
		}
		return redirect()->route('suppliers.index')->with('success', 'Success, supplier have been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.update', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $supplier->supplier_name = $request->supplier_name;
		$supplier->address = $request->address;
		$supplier->phone = $request->phone;
		
		/*if ($request->hasFile('avatar')) {
			//Delete old avatar
			if ($supplier->avatar) {
				Storage::delete($supplier->avatar);
			}
			//Store Avatar
			$avatar_path = $request->file('avatar')->store('suppliers');
			//Save to Database
			$supplier->avatar = $avatar_path;		
		}*/
		
		if (!$supplier->save()) {
			return redirect()->back()->with('error', 'Sorry, There is a problem while updating supplier.');
		}
		return redirect()->route('suppliers.index')->with('success', 'Success, Supplier has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        /*if ($supplier->avatar) {
			Storage::delete($supplier->avatar);
		}*/
		
		$supplier->delete();
			
			return response()->json([
				'success' => true
			]);
    }
}
