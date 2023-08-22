@extends('layouts.admin')

@section('title', 'Sell Orders List')
@section('content-header', 'Sell Order List')


@section('content')
<div class="card">
    <div class="card-body">
		<table class="table">
			<thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>UoM</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{$order->id}}</td>
                    <td>{{$order->product->name}}</td>
					<td>{{$order->quantity}}</td>
                    <td>{{$order->product->uom_prod->name}}</td>
					<td>{{ config('settings.currency_symbol') }} {{ number_format($order->product->price, 0)}}</td>
					<td>{{ config('settings.currency_symbol') }} {{ number_format($order->product->price * $order->quantity, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
			<tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{ config('settings.currency_symbol') }} {{ number_format($total, 0) }}</th>
                </tr>
            </tfoot>
        </table>
	</div>
</div>
@endsection