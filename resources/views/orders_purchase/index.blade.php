@extends('layouts.admin')

@section('title', 'Orders List')
@section('content-header', 'Buy Order List')
@section('content-actions')
    <a href="{{route('purchase.index')}}" class="btn btn-primary">Open Purchase</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <form action="{{route('orders.index')}}">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="date" name="start_date" class="form-control" value="{{request('start_date')}}" />
                        </div>
                        <div class="col-md-5">
                            <input type="date" name="end_date" class="form-control" value="{{request('end_date')}}" />
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>No Invoice</th>
                    <th>Supplier Name</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Created At</th>
					<th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{
                        'PO-' . $order->created_at->format('Y') . '/' . $order->created_at->format('dm') . '/' . str_pad($order->id, 4, '0', STR_PAD_LEFT)
                    }}</td>
                    <td>{{$order->supplier->supplier_name}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{$order->formattedTotal()}}</td>
                    <td>
                        @if($order->receivedAmount() == 0)
                            <span class="badge badge-danger">Not Paid</span>
                        @elseif($order->receivedAmount() < $order->total())
                            <span class="badge badge-warning">Partial</span>
                        @elseif($order->receivedAmount() == $order->total())
                            <span class="badge badge-success">Paid</span>
                        @elseif($order->receivedAmount() > $order->total())
                            <span class="badge badge-info">Change</span>
                        @endif
                    </td>
                    <td>{{$order->created_at}}</td>
					<td>
					<a href="{{ route('orders.purchase_order', $order) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                    <a href="#" class="btn btn-warning">
                        <i class="fas fa-print"></i>
                    </a>
					</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th>{{ config('settings.currency_symbol') }} {{ number_format($receivedAmount, 2) }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        {{ $orders->render() }}
    </div>
</div>
@endsection

