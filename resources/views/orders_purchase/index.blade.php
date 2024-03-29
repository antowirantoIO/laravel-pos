@extends('layouts.admin')

@section('title', 'Orders List')
@section('content-header', 'Buy Order List')
@section('content-actions')
    <!-- <a href="{{route('purchase.index')}}" class="btn btn-primary">Open Purchase</a> -->
    <ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Buy Order</li>
</ol>
@endsection

@section('content')
<div class="card card-primary card-outline">
<div class="card-header bg-white">
        <div class="d-flex justify-content-between">
        <div>
        <form action="{{route('orders.purchase')}}">
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
        <a href="{{route('purchase.index')}}" class="btn btn-primary">Open Purchase</a>
        </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-datatable">
        <thead style="background: #F4F6F9">
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
                    <td>{{
                        $order->created_at->isoFormat('D MMMM Y')
                    }}</td>
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
                    <th>{{ config('settings.currency_symbol') }} {{ number_format($total, 2) }}</th>
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
        
    });
</script>
@endsection

