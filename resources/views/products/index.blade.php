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
                    <th>NO</th>
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
                    <td id="barcode" style="cursor: pointer" onClick="download()">{!! 
                        DNS1D::getBarcodeHTML($product->barcode, 'EAN13')
                    !!}
						<center>
                        {{$product->barcode}}
                        </center></td>
                    <td class="text-nowrap">{{ config('settings.currency_symbol') }} {{number_format($product->price)}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{number_format($product->purchase_price)}}</td>
                    <td>{{
                        $product->expired_date != null ? date('d-m-Y', strtotime($product->expired_date)) : ''
                    }}</td>
                    <td>{{$product->quantity}}</td>
                    <td>{{
                        $product->uom_prod->name
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>

<script>

        function download() {
            // Mendapatkan elemen dengan ID "barcode" untuk diubah menjadi gambar PNG
            const barcodeElement = document.getElementById("barcode");

            // Mengubah elemen menjadi gambar PNG menggunakan html2canvas
            html2canvas(barcodeElement).then(function(canvas) {
                // Membuat tautan unduhan untuk gambar PNG
                const link = document.createElement("a");
                link.href = canvas.toDataURL("image/png");
                link.download = "barcode.png";
                // Menambahkan tautan ke dalam dokumen dan mengkliknya untuk mengunduh
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        }

    $(document).ready(function () {

        var table = $('.table-datatable').DataTable({
        "order": [[ 0, "asc" ]],
        columnDefs: [
            { width: '10%', targets: 0 },
            { width: '10%', targets: 1 },
            { width: '10%', targets: 2 },
            { width: '10%', targets: 3 },
            { width: '10%', targets: 4 },
            { width: '10%', targets: 5 },
            { width: '10%', targets: 6 },
            { width: '10%', targets: 7 },
            { width: '10%', targets: 8 },
            { width: '10%', targets: 9 },
        ],
        fixedColumns: true,
        paging: false,
        scrollCollapse: true,
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
