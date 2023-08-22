<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Laporan Laba Kotor</title>
    <style>
        body {
            font-family: 'Calibri', sans-serif;
        }
        .report-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .sub-header {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-weight: normal;
        }
        .data-table th, .data-table td {
            padding: 8px;
            text-align: left;
        }
        .data-table th {
            border-bottom: 1px solid #000;
        }
        .data-table td.border-bottom {
            border-bottom: 1px solid #000;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <p class="header">
            {{config('app.name')}}
        </p>
        <p class="sub-header">
            Laporan Laba Kotor
        </p>
        <p class="sub-header">
            Periode {{$month}} {{$year}}
        </p>

        <br/>
        <br/>
        
        <table class="data-table">
            <tr>
                <th>Pendapatan</th>
                <td>Rp {{ number_format($penjualan, 0, '.', ',') }}</td>
            </tr>
            <tr>
                <th class="border-bottom">Potongan Penjualan</th>
                <td class="border-bottom">Rp {{ number_format($potongan_penjualan, 0, '.', ',') }}</td>
            </tr>
            <tr>
                <th>Pendapatan Bersih</th>
                <td>Rp {{ number_format($penjualan_bersih, 0, '.', ',') }}</td>
            </tr>
            <br/>
            <br/>
            <tr>
                <th class="border-bottom">Harga Pokok Penjualan</th>
                <td class="border-bottom">Rp {{ number_format($hpp, 0, '.', ',') }}</td>
            </tr>
            <tr>
                <th>Laba Kotor</th>
                <td>Rp {{ number_format($laba_kotor, 0, '.', ',') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
