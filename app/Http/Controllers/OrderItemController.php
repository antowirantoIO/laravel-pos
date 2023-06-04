<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;

class OrderItemController extends Controller
{
    public function index(Request $request) {
		$order_items = OrderItem::with('product')->get();
		return view('order_items.index', compact('order_items'));
		//return $order_items;
	}
}
