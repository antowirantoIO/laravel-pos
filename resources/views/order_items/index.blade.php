@extends('layouts.admin')

@section('title', 'Orders List')
@section('content-header', 'Order List')


@section('content')
<div class="card">
    <div class="card-body">
		<table class="table">
			<thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order_items as $order)
                <tr>
                    <td>{{$order->order_id}}</td>
                    <td>{{$order->product->name}}</td>
                    <td>{{$order->quantity}}</td>
                    <td>{{$order->price}}</td>
                    <td>{{$order->barcode}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

	</div>
</div>
@endsection