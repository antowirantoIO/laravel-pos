@extends('layouts.admin')

@section('title', 'Product List')
@section('content-header', 'Product List')
@section('content-actions')
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Product</li>
</ol>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
<div class="card card-primary card-outline">
<div class="card-header bg-white">
		<div>
		<a href="{{route('products.create')}}" class="btn text-white btn-primary">Tambah Product</a>
		</div>
	</div>
    <div class="card-body">
        <table class="table table-datatable">
            <thead style="background: #F4F6F9">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Barcode</th>
                    <th>Sell Price</th>
                    <th>Purchase Price</th>
                    <th>Expired Date</th>
                    <th>Quantity</th>
                    <th>UoM</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $no = 1;
                @endphp
                @foreach ($products as $product)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$product->name}}</td>
                    <td>{!! DNS1D::getBarcodeHTML("$product->barcode",'EAN13',2,100) ?? null !!}
						{{$product->barcode}}</td>
                    <td class="text-nowrap">{{ config('settings.currency_symbol') }} {{number_format($product->price)}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{number_format($product->purchase_price)}}</td>
                    <td>{{
                        $product->expired_date != null ? date('d-m-Y', strtotime($product->expired_date)) : ''
                    }}</td>
                    <td>{{$product->quantity}}</td>
                    <td>{{
                        \App\Models\Product::UOM[$product->uom] ?? ''
                    }}</td>
                    <td>
                        <span
                            class="right badge badge-{{ $product->status ? 'success' : 'danger' }}">{{$product->status ? 'Active' : 'Inactive'}}</span>
                    </td>
                    <td class="d-flex" style="gap: 5px">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-primary"><i
                                class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger btn-delete" data-url="{{route('products.destroy', $product)}}"><i
                                class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        var table = $('.table-datatable').DataTable({
        "order": [[ 5, "desc" ]],
        scrollX: true,
        });
        $(document).on('click', '.btn-delete', function () {
            $this = $(this);
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success ml-2',
                    cancelButton: 'btn btn-danger mr-2'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "Do you really want to delete this product?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    $.post($this.data('url'), {_method: 'DELETE', _token: '{{csrf_token()}}'}, function (res) {
                        $this.closest('tr').fadeOut(500, function () {
                            $(this).remove();
                        })
                    })
                }
            })
        })
    })
</script>
@endsection
