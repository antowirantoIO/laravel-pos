<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\HPPProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderStoreRequest;
use Carbon\Carbon;

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
        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', dateformat_custom());
        $due_day = $datetime->addDays($request->due_date)->format('Y-m-d H:i:s');
        $order = Order::create([
            'user_id' => $request->user()->id,
            'supplier_id' => $request->supplier_id ?? null,
            'due_day' =>  $due_day ?? null,
            'created_at' => dateformat_custom(),
            'updated_at' => dateformat_custom(),
        ]);

        if($request->supplier_id != null){
            
            $purchase = $request->cart;
        
            $sum = 0;

            foreach ($purchase as $item) {
                $order->items()->create([
                    'price' => $item["purchase_price"] * $item["pivot"]["quantity"],
                    'quantity' => $item["pivot"]["quantity"],
                    'product_id' => $item["id"],
                    'created_at' => dateformat_custom(),
                    'updated_at' => dateformat_custom(),
                ]);
                
                $sum += $item["purchase_price"] * $item["pivot"]["quantity"];
                $product = Product::find($item["id"]);

                $hpp = HPPProduct::where('product_id', $item["id"])->where('bulan', date('m', strtotime(dateformat_custom())))->where('tahun', date('Y', strtotime(dateformat_custom())))->first();

                $hppValue = $hpp->hpp;
                $productQuantity = $product->quantity;
                $itemPurchasePrice = $item['purchase_price'];
                $itemPivotQuantity = $item['pivot']['quantity'];
                
                $itemHPP = ($hppValue * $productQuantity) + ($itemPurchasePrice * ($itemPivotQuantity));
                $itemQTY = $productQuantity + $itemPivotQuantity;

                $calculate = $itemHPP / $itemQTY;

                $hpp->hpp = $calculate;
                $hpp->save();

                $product->quantity = $product->quantity + $item["pivot"]["quantity"];
                $product->purchase_price = $item["purchase_price"];
                $product->expired_date = $item["expired_date"];
                $product->save();
            }

            $order->payments()->create([
                'amount' => $sum,
                'supplier_id' => $request->supplier_id ?? null,
                'user_id' => $request->user()->id,
                'created_at' => dateformat_custom(),
                'updated_at' => dateformat_custom(),
            ]);

            return 'success';
        }

        $cart = $request->cart;

        foreach ($cart as $item) {
            $order->items()->create([
                'price' => $item["price"] * $item["pivot"]["quantity"],
                'quantity' => $item["pivot"]["quantity"],
                'product_id' => $item["id"],
                'created_at' => dateformat_custom(),
                'updated_at' => dateformat_custom(),
            ]);

            $product = Product::find($item["id"]);
            $product->quantity = $product->quantity - $item["pivot"]["quantity"];
            $product->save();
        }

        if($request->due_date == null) {
            $order->payments()->create([
                'amount' => $request->amount,
                'user_id' => $request->user()->id,
                'created_at' => dateformat_custom(),
                'updated_at' => dateformat_custom(),
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
