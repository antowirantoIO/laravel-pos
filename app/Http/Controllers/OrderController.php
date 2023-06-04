<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request) {
        $orders = new Order();
        if($request->start_date) {
            $orders = $orders->where('created_at', '>=', $request->start_date);
        }
        if($request->end_date) {
            $orders = $orders->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        $orders = $orders->with(['items', 'payments', 'customer'])->latest()->paginate(10);

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
        if($request->start_date) {
            $orders = $orders->where('created_at', '>=', $request->start_date);
        }
        if($request->end_date) {
            $orders = $orders->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        $orders = $orders->where('supplier_id', '!=', null)->with(['items', 'payments', 'supplier'])->latest()->paginate(10);

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
        $order = Order::create([
            //'customer_id' => $request->customer_id,
            'user_id' => $request->user()->id,
            'supplier_id' => $request->supplier_id ?? null,
        ]);

        if($request->supplier_id != null){
            $purchase = $request->user()->purchase()->get();
            
            $sum = 0;

            foreach ($purchase as $item) {
                $order->items()->create([
                    'price' => $item->price * $item->pivot->quantity,
                    'quantity' => $item->pivot->quantity,
                    'product_id' => $item->id,
                ]);
                $sum += $item->price * $item->pivot->quantity;
                $item->quantity = $item->quantity + $item->pivot->quantity;
                $item->save();
            }

            $request->user()->purchase()->detach();

            $order->payments()->create([
                'amount' => $sum,
                'supplier_id' => $request->supplier_id ?? null,
                'user_id' => $request->user()->id,
            ]);

            return 'success';
        }

        $cart = $request->user()->cart()->get();
        foreach ($cart as $item) {
            $order->items()->create([
                'price' => $item->price * $item->pivot->quantity,
                'quantity' => $item->pivot->quantity,
                'product_id' => $item->id,
            ]);
            $item->quantity = $item->quantity - $item->pivot->quantity;
            $item->save();
        }
        $request->user()->cart()->detach();
        $order->payments()->create([
            'amount' => $request->amount,
            'user_id' => $request->user()->id,
        ]);
        return 'success';
    }
		public function show($orders)
	{
		//$orders = Order::all();
		$orders = OrderItem::where('order_id', $orders)->get();
		//$total = $orders->sum('total()');
		$total = $orders->map(function($i) {
        return $i->total();
        })->sum();
		return view('orders.show', compact('orders','total'));
		//dd($orders);
		//return view('orders.show')->with('order', $order);
		//return($order);
		//return DB::table('order')->join('order_items', 'order_items.order_id', '=', 'orders.id')->where('orders.id', "$order")->get();
	}
}
