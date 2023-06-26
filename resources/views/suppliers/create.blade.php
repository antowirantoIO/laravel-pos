@extends('layouts.admin')

@section('title', 'Create Supplier')
@section('content-header', 'Create Supplier')

@section('content')
<form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="card">
	<div class="card-body">
<div class="form-group">
	<label for="supplier_name">Supplier Name</label>
	<input type="text" name="supplier_name" class="form-control @error('supplier_name') is-invalid @enderror" id="supplier_name" placeholder="Supplier Name" value="{{ old('supplier_name') }}">
	@error('supplier_name')
	<span class="invalid-feedback" role="alert">
		<strong>{{ $message }}</strong>
	</span>
	@enderror
	</div>

<div class="form-group">
	<label for="address">Address</label>
	<input type="text" name="address" class="form-control @error('address') is-invalid @enderror" id="address" placeholder="Address" value="{{ old('address') }}">
	@error('address')
	<span class="invalid-feedback" role="alert">
		<strong>{{ $message }}</strong>
	</span>
	@enderror
	</div>
	
<!--<div class="form-group">
	<label for="image">Image</label>
	<div class="custom-file">
	<input type="file" name="image" class="custom-file-input" id="image">
		<label class="custom-file-label" for="image">Choose File</label>
	</div>
	@error('image')
	<span class="invalid-feedback" role="alert">
		<strong>{{ $message }}</strong>
	</span>
	@enderror
	</div>-->
	
<div class="form-group">
	<label for="phone">Phone</label>
	<input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" placeholder="Phone" value="{{ old('phone') }}">
	@error('phone')
	<span class="invalid-feedback" role="alert">
		<strong>{{ $message }}</strong>
	</span>
	@enderror
	</div>
		
<!--<div class="form-group">
	<label for="avatar">Avatar</label>
	<div class="custom-file">
	<input type="file" name="avatar" class="custom-file-input" id="avatar">
		<label class="custom-file-label" for="avatar">Choose File</label>
	</div>
	@error('avatar')
	<span class="invalid-feedback" role="alert">
		<strong>{{ $message }}</strong>
	</span>
	@enderror
	</div>-->
	
	<button class="btn btn-primary" type="submit">Create</button>
	<a href="/admin/suppliers" class="btn btn-danger">Cancle</a>

</form>
</div>
	</div>
@endsection

@section('js')
<!--<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
	$(document).ready(function () {
		bsCustomFileInput.init();
	});
</script>-->
@endsection