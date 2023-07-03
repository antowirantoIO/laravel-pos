<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $orders = Order::with(['items', 'payments'])->get();
        $customers_count = Customer::count();

        // ambil data produk yang 10 ahri lagi akan expired 
        $expired_product = \App\Models\Product::where('expired_date', '<=', date('Y-m-d', strtotime('+10 days')))->count();

        // ambil data order due date nya hari ini
        $currentDate = Carbon::now()->format('Y-m-d');
        $orders_due = Order::whereDate('due_day', $currentDate)->get();
        $amount = 0;
        foreach($orders_due as $order) {
            $payment = Payment::where('order_id', $order->id)->first();

            if($payment == null) {
                $items = OrderItem::where('order_id', $order->id)->get();
                foreach($items as $item) {
                    $amount += $item->price * $item->quantity;
                }
                $order->payments()->create([
                    'amount' => $amount,
                    'user_id' => $request->user()->id,
                ]);

                $amount = 0;
            }
        }

        $expired_product_list = \App\Models\Product::where('expired_date', '<=', date('Y-m-d', strtotime('+1 days')))->get();

        Session::put('expired_product', $expired_product);
        Session::put('expired_product_list', $expired_product_list);
        Session::put('expired_product_list_lenght', $expired_product_list->count());

        return view('home', [
            'orders_count' => $orders->count(),
            'income' => $orders->map(function($i) {
                if($i->receivedAmount() > $i->total()) {
                    return $i->total();
                }
                return $i->receivedAmount();
            })->sum(),
            'outcome' => $orders->map(function($i) {
                if($i->supplier_id) {
                    return $i->total();
                }
            })->sum(),
            'buys_count' => $orders->filter(function($i) {
                return $i->supplier_id;
            })->count(),
            'customers_count' => $customers_count
        ]);
    }
}
