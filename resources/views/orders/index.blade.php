@extends('layouts.admin')

@section('title', 'Orders List')
@section('content-header', 'Sell Order List')
@section('content-actions')
    <!-- <a href="{{route('cart.index')}}" class="btn btn-primary">Open POS</a> -->
    <ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Sell Order</li>
</ol>
@endsection

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between">
        <div>
        <form action="{{route('orders.index')}}">
            <div class="d-flex" style="gap: 10px;">
                <div>
                    <input type="date" name="start_date" class="form-control" value="{{request('start_date')}}" />
                </div>
                <div>
                    <input type="date" name="end_date" class="form-control" value="{{request('end_date')}}" />
                </div>
                <div>
                    <button class="btn btn-outline-primary" type="submit">Cari</button>
                </div>
            </div>
        </form>
        </div>
        <div>
        <a href="{{route('cart.index')}}" class="btn btn-primary">Open POS</a>
        </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-datatable">
        <thead style="background: #F4F6F9">
                <tr>
                    <th>No Invoice</th>
                    <th>Total</th>
                    <th>Received Amount</th>
                    <th>Status</th>
                    <th>To Pay</th>
                    <th>Created At</th>
                    <th>Due Date</th>
					<th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{
                        'SO-' . $order->created_at->format('Y') . '/' . $order->created_at->format('dm') . '/' . str_pad($order->id, 4, '0', STR_PAD_LEFT)
                    }}</td>
                    <td>{{ config('settings.currency_symbol') }} {{$order->formattedTotal()}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{$order->formattedReceivedAmount()}}</td>
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
                    <td>{{config('settings.currency_symbol')}} {{number_format($order->total() - $order->receivedAmount(), 2)}}</td>
                    <td>{{
                        // format to 10 Juni 2022
                        $order->created_at->isoFormat('D MMMM Y')
                    }}</td>
                    @php
                    if($order->receivedAmount() == $order->total() || $order->due_day == null){
                        $order->due_day = null;
                    }
                    @endphp
                    <td>{{
                        $order->due_day != null ? date('d M Y', strtotime($order->due_day)) : '-'
                    }}</td>
					<td>
					<a href="{{ route('orders.show', $order) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
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
                    <th>{{ config('settings.currency_symbol') }} {{ number_format($total, 2) }}</th>
                    <th>{{ config('settings.currency_symbol') }} {{ number_format($receivedAmount, 2) }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@section('js')
<script>
    var table = $('.table-datatable').DataTable({
        "order": [[ 6, "desc" ]]
    });
</script>
@endsection

