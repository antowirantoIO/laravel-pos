@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
    <div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                <div>
                <label for="laporan">Laporan</label>
                <select name="laporan" class="form-control select" id="laporan">
                    <option value="laba_rugi">Laporan Laba Kotor</option>
                    <option value="persediaan_barang">Laporan Persediaan Barang</option>
                    <option value="barang_expired">Laporan Barang Akan Expired</option>
                </select>
                </div>
                <div class="pt-4 hiden">
                <label for="month">Bulan</label>
                <select name="month" class="form-control select" id="month">
                    @foreach($months as $month)
                        <option value="{{ 
                            strtolower($month)
                        }}" {{ old('month') == $month ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
                </div>
                <div class="pt-4 hiden">
                <label for="year">Tahun</label>
                <select name="year" class="form-control select" id="year">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex ms-4" style="gap:5px">
            <div>
                <button type="button" id="download_button" class="btn btn-primary">
                    Download Report
                </button>
            </div>
            <div>
                <a href="/reports" class="btn btn-danger">
                    Cancel
                </a>
            </div>
            </div>
        </div>
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
<script>
    $('.select').select2({
        placeholder: 'Select an option'
        });

    var jenis_laporan = "laba_rugi"
    var bulan = "{{$selected_month}}"
    var tahun = "{{ $selected_year }}"

    $("#laporan").change(function(){
        jenis_laporan = $(this).val()
        if(jenis_laporan == "persediaan_barang" || jenis_laporan == "barang_expired"){
            $(".hiden").hide()
        } else {
            $(".hiden").show()
        }
        console.log(jenis_laporan)
    })
    $("#month").change(function(){
        bulan = $(this).val()
        console.log(bulan)
    })
    $("#year").change(function(){
        tahun = $(this).val()
        console.log(tahun)
    })

    $("#download_button").click(function(e){
        console.log("clicked")
        e.preventDefault()
        if(jenis_laporan == "" || bulan == "" || tahun == ""){
            alert("Harap isi semua field")
        }else{
            if(jenis_laporan == "laba_rugi"){
                window.location.href = "/admin/reports/labakotor?bulan="+bulan+"&tahun="+tahun
            } else {
                window.location.href = "/admin/reports/download?jenis_laporan="+jenis_laporan+"&bulan="+bulan+"&tahun="+tahun
            }
        }
    })
</script>
@endsection