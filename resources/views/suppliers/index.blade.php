@extends('layouts.admin')

@section('title', 'Supplier List')
@section('content-header', 'Supplier List')
@section('content-actions')
<a href="{{ route('suppliers.create') }}" class="btn btn-primary">Create Supplier</a>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
<div class="card">
<div class="card-body">
<table class="table">
	<thead>
	<tr>
		<th>ID</th>
		<!--<th>Avatar</th>-->
		<th>Supplier Name</th>
		<!--<th>Image</th>-->
		<th>Address</th>
		<th>Phone</th>
		<th>Created At</th>
		<th>Updated At</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
	@foreach ($suppliers as $supplier)
	<tr>
		<td>{{$supplier->id}}</td>
		<!--<td>
			<img width="50" src="{{$supplier->getAvatarUrl()}}" alt=""</td>-->
		<td>{{$supplier->supplier_name}}</td>
		<td>{{$supplier->address}}</td>
		<td>{{$supplier->phone}}</td>
		<td>{{$supplier->created_at}}</td>
		<td>{{$supplier->updated_at}}</td>
		<td>
		<!--<a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>-->
		<a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-info"><i class="fas fa-edit"></i></a></a>
		<button class="btn btn-danger btn-delete" data-url="{{ route('suppliers.destroy', $supplier) }}"><i class="fas fa-trash"></i></button>
		</td>
	</tr>
	@endforeach
	</tbody>
</table>
{{ $suppliers->render() }}
</div>
	</div>
@endsection

@section('js')
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
	//$(document).ready(function () {
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
					  text: "Delete the supplier?",
					  icon: 'warning',
					  showCancelButton: true,
					  confirmButtonText: 'Delete',
					  cancelButtonText: 'Cancel',
					  reverseButtons: true
					}).then((result) => {
					  if (result.isConfirmed) {
						  $.post($this.data('url'), {_method: 'DELETE', _token: '{{csrf_token() }}'}, function (res) {
							  $this.closest('tr').fadeOut(500, function () {
								  $(this).remove();
							  })
						  })
					  }
				})
		})
	
</script>
@endsection