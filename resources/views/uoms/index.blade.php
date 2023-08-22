@extends('layouts.admin')

@section('title', 'UOM List')
@section('content-header', 'UOM List')
@section('content-actions')
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">UOM</li>
</ol>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
@if($errors->any())
<div class="alert alert-danger">
	@if ($errors->any())
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	@endif
</div>

@endif
<div class="card card-primary card-outline">
	<div class="card-header bg-white">
		<div>
		<a href="#" data-toggle="modal" data-target="#exampleModal" class="btn text-white btn-primary">Tambah UOM</a>
		</div>
	</div>
<div class="card-body">
<table class="table table-datatable">
<thead style="background: #F4F6F9">
	<tr>
		<th>ID</th>
		<th>UOM Name</th>
        <th>Actions</th>
	</tr>
	</thead>
	<tbody>
	@php 
		$no = 1;
	@endphp
	@foreach ($uoms as $uom)
	<tr>
		<td>{{$no++}}</td>
		<td>{{$uom->name}}</td>
		<td>
		<a href="#" id="btn-update" data-name="{{$uom->name}}" class="btn btn-info"><i class="fas fa-edit"></i></a></a>
		<button class="btn btn-danger btn-delete" data-url="{{ route('uom.destroy', $uom) }}"><i class="fas fa-trash"></i></button>
		</td>
	</tr>
	@endforeach
	</tbody>
</table>
</div>
	</div>

    <!-- Modal -->
<div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="modalUpdateLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUpdateLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('uom.store') }}" method="POST" id="form">

      <div class="modal-body">
            @csrf
            <div class="form-group">
                <label for="name">UOM Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="UOM Name">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>

      </div>
      </form>

    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    var table = $('.table-datatable').DataTable({
    });

    $('#btn-update').on('click', function () {
        $('#modalUpdate').modal('show');
        $('#modalUpdateLabel').text('Update UOM');
        $('#name').val($(this).data('name'));
        $('#form').attr('action', '{{ route('uom.update', $uom) }}');
        $('#form').append('<input type="hidden" name="_method" value="PUT">');
    })

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