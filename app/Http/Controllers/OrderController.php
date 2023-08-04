<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderStoreRequest;

class OrderController extends Controller
{
    public function index(Request $request) {
        $orders = new Order();
        $orders->orderBy('created_at', 'desc');
        if($request->start_date) {
            $orders = $orders->where('created_at', '>=', $request->start_date);
        }
        if($request->end_date) {
            $orders = $orders->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        $orders = $orders->where('supplier_id', '=', null)->with(['items', 'payments', 'customer'])->latest()->get();

        $total = $orders->map(function($i) {
            return $i->total();
        })->sum();
        $receivedAmount = $orders->map(function($i) {
            return $i->receivedAmount();
        })->sum();
        
        return view('orders.index', compact('orders', 'total', 'receivedAmount'));
    }

    public function purchase(Request $request)
    {
        $orders = new Order();
        $orders->orderBy('created_at', 'desc');
        if($request->start_date) {
            $orders = $orders->where('created_at', '>=', $request->start_date);
        }
        if($request->end_date) {
            $orders = $orders->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        $orders = $orders->where('supplier_id', '!=', null)->with(['items', 'payments', 'supplier'])->latest()->get();

        $total = $orders->map(function($i) {
            return $i->total();
        })->sum();
        $receivedAmount = $orders->map(function($i) {
            return $i->receivedAmount();
        })->sum();

        return view('orders_purchase.index', compact('orders', 'total', 'receivedAmount'));
    }

    public function store(OrderStoreRequest $request)
    {
        $due_day = date('Y-m-d', strtotime('+' . $request->due_date . ' days'));
        $order = Order::create([
            'user_id' => $request->user()->id,
            'supplier_id' => $request->supplier_id ?? null,
            'due_day' =>  $due_day ?? null,
        ]);

        if($request->supplier_id != null){
            
            $purchase = $request->cart;
        
            $sum = 0;

            foreach ($purchase as $item) {
                $order->items()->create([
                    'price' => $item["purchase_price"] * $item["pivot"]["quantity"],
                    'quantity' => $item["pivot"]["quantity"],
                    'product_id' => $item["id"],
                ]);
                
                $sum += $item["purchase_price"] * $item["pivot"]["quantity"];

                $product = Product::find($item["id"]);
                $product->quantity = $product->quantity + $item["pivot"]["quantity"];
                $product->save();
            }

            $order->payments()->create([
                'amount' => $sum,
                'supplier_id' => $request->supplier_id ?? null,
                'user_id' => $request->user()->id,
            ]);

            return 'success';
        }

        $cart = $request->cart;

        foreach ($cart as $item) {
            $order->items()->create([
                'price' => $item["price"] * $item["pivot"]["quantity"],
                'quantity' => $item["pivot"]["quantity"],
                'product_id' => $item["id"],
            ]);

            $product = Product::find($item["id"]);
            $product->quantity = $product->quantity - $item["pivot"]["quantity"];
            $product->save();
        }

        if($request->due_date == null) {
            $order->payments()->create([
                'amount' => $request->amount,
                'user_id' => $request->user()->id,
            ]);
        }
        return 'success';
    }
		public function show($orders)
	{
		//$orders = Order::all();
		$orders = OrderItem::where('order_id', $orders)->with('product')->get();

		//$total = $orders->sum('total()');
		$total = $orders->map(function($i) {
            return $i->product->price * $i->quantity;
        })->sum();

		return view('orders.show', compact('orders','total'));
		//dd($orders);
		//return view('orders.show')->with('order', $order);
		//return($order);
		//return DB::table('order')->join('order_items', 'order_items.order_id', '=', 'orders.id')->where('orders.id', "$order")->get();
	}

    public function show_purchase($orders)
	{
		//$orders = Order::all();
		$orders = OrderItem::where('order_id', $orders)->get();
		//$total = $orders->sum('total()');
		$total = $orders->map(function($i) {
            return $i->product->purchase_price * $i->quantity;
        })->sum();
		return view('orders_purchase.show', compact('orders','total'));
		//dd($orders);
		//return view('orders.show')->with('order', $order);
		//return($order);
		//return DB::table('order')->join('order_items', 'order_items.order_id', '=', 'orders.id')->where('orders.id', "$order")->get();
	}
}
