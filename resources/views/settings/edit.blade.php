@extends('layouts.admin')

@section('title', 'Update Settings')
@section('content-header', 'Update Settings')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('settings.store') }}" method="post">
            @csrf

            <div class="form-group">
                <label for="app_name">App name</label>
                <input type="text" name="app_name" class="form-control @error('app_name') is-invalid @enderror" id="app_name" placeholder="App name" value="{{ old('app_name', config('settings.app_name')) }}">
                @error('app_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="app_description">App description</label>
                <textarea name="app_description" class="form-control @error('app_description') is-invalid @enderror" id="app_description" placeholder="App description">{{ old('app_description', config('settings.app_description')) }}</textarea>
                @error('app_description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="currency_symbol">Currency symbol</label>
                <input type="text" name="currency_symbol" class="form-control @error('currency_symbol') is-invalid @enderror" id="currency_symbol" placeholder="Currency symbol" value="{{ old('currency_symbol', config('settings.currency_symbol')) }}">
                @error('currency_symbol')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            {{-- <div class="form-group">
                <label for="warning_quantity">Warning quantity</label>
                <input type="text" name="warning_quantity" class="form-control @error('warning_quantity') is-invalid @enderror" id="warning_quantity" placeholder="Warning quantity" value="{{ old('warning_quantity', config('settings.warning_quantity')) }}">
                @error('warning_quantity')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div> --}}
            <div class="form-group">
                <!-- switch -->
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="customSwitch1" value="1" name="date_system" {{ old('date_system', config('settings.date_system')) == 1 ? 'checked' : '' }}>
                    <label class="custom-control-label" for="customSwitch1">System Date Time</label>
                </div>
                <input type="datetime-local" {{ config('settings.date_system') != 1 ? 'disabled' : '' }} name="date_system_value" class="form-control mt-3 @error('date_system_value') is-invalid @enderror" id="date_system_value" placeholder="Date Time" value="{{ old('date_system_value', config('settings.date_system_value')) }}">

                @error('date_system')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Change Setting</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#customSwitch1').change(function() {
            if ($(this).prop('checked')) {
                $('#date_system_value').prop('disabled', false);
            } else {
                $('#date_system_value').prop('disabled', true);
            }
        });
    });
</script>
@endsection
