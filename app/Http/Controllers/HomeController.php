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

        // carbon from dateformat_custom from 2023-08-20 13:46:00 + 10 days
        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', dateformat_custom());

        $expired_product_list = \App\Models\Product::where('expired_date', '<=', $datetime->addDays(1)->format('Y-m-d H:i:s'))->get();

        $expired_product = $expired_product_list->count();

        // ambil data order due date nya hari ini
        $datetime->subDays(1);
        $currentDate = $datetime->format('Y-m-d H:i:s');

        // $orders_due = Order::whereDate('due_day', $currentDate)->get();
        $orders_due = Order::where('due_day', '<=', $currentDate)->get();
        // dd($orders_due);
        $amount = 0;
        foreach($orders_due as $order) {
            $payment = Payment::where('order_id', $order->id)->first();

            if($payment == null) {
                $items = OrderItem::where('order_id', $order->id)->get();
                foreach($items as $item) {
                    $amount += $item->product->price * $item->quantity;
                    // echo $item->product->price . ' * ' . $item->quantity . ' = ' . $amount . '<br>';
                }
                // dd($amount);
                $order->payments()->create([
                    'amount' => $amount,
                    'user_id' => $request->user()->id,
                ]);

                $amount = 0;
            }
        }


        Session::put('expired_product', $expired_product);
        Session::put('expired_product_list', $expired_product_list);
        Session::put('expired_product_list_lenght', $expired_product_list->count());

        return view('home', [
            'orders_count' => Order::where('supplier_id', '=', null)->count(),
            'income' => Order::where('supplier_id', '=', null)->get()->map(function($i) {
                return $i->total();
            })->sum(),
            'outcome' => Order::where('supplier_id', '!=', null)->get()->map(function($i) {
                return $i->total();
            })->sum(),
            'buys_count' => Order::where('supplier_id', '!=', null)->count(),
            'customers_count' => $customers_count
        ]);
    }
}
