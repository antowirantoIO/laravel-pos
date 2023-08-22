@extends('layouts.admin')

@section('title', 'Create Product')
@section('content-header', 'Create Product')

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
                    placeholder="Name" value="{{ old('name') }}">
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>


            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                    id="description" placeholder="description">{{ old('description') }}</textarea>
                @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="barcode">Barcode</label>
                <input type="number"  name="barcode" class="form-control @error('barcode') is-invalid @enderror"
                    id="barcode" placeholder="barcode" value="{{ old('barcode') }}">
                @error('barcode')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="expired_date">Expired Date</label>
                <input type="date" name="expired_date" class="form-control @error('expired_date') is-invalid @enderror"
                    id="expired_date" value="{{ old('expired_date') }}">
                @error('expired_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Sell Price</label>
                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" id="price"
                    placeholder="price" value="{{ old('price') }}">
                @error('price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="purchase_price">Buy Price</label>
                <input type="number" name="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" id="purchase_price"
                    placeholder="purchase price" value="{{ old('purchase_price') }}">
                @error('purchase_price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>


            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="text" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                    id="quantity" placeholder="Quantity" value="{{ old('quantity', 1) }}">
                @error('quantity')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="uom">UoM</label>

                <select name="uom" class="form-control select @error('uom') is-invalid @enderror" style="height: 5rem" id="uom">
                    <option value="">Select UoM</option>
                    @foreach ($uom as $value)
                    <option value="{{ $value->id }}" {{ old('uom') == $uom ? 'selected' : '' }}>
                        {{ $value->name }}</option>
                    @endforeach
                </select>

                @error('quantity')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                    <option value="1" {{ old('status') === 1 ? 'selected' : ''}}>Active</option>
                    <option value="0" {{ old('status') === 0 ? 'selected' : ''}}>Inactive</option>
                </select>
                @error('status')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button class="btn btn-primary" type="submit">Create</button>
            <a href="/admin/products" class="btn btn-danger">Cancel</a>
        </form>
    </div>
</div>
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .select2-container .select2-selection--single{
        height: 40px !important;
    }
</style>
@endsection

@section('js')
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(document).ready(function () {
        bsCustomFileInput.init();
    });
    $('.select').select2({
        placeholder: 'Select an option'
        });
</script>
@endsection
