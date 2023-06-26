@extends('layouts.admin')

@section('title', 'Buy Orders List')
@section('content-header', 'Buy Order List')


@section('content')
<div class="card">
    <div class="card-body">
		<table class="table">
			<thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>UOM</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{$order->id}}</td>
                    <td>{{$order->product->name}}</td>
                    <td>{{$order->product->uom}}</td>
					<td>{{$order->quantity}}</td>
					<td>{{ config('settings.currency_symbol') }} {{ number_format($order->product->purchase_price, 0)}}</td>
					<td>{{ config('settings.currency_symbol') }} {{ number_format($order->product->purchase_price * $order->quantity, 0) }}</td>

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