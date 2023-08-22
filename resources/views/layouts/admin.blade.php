<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', config('app.name'))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- overlayScrollbars -->
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    <!-- Google Font: Source Sans Pro -->
    <script src="
    https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js
    "></script>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <script src="https://adminlte.io/themes/v3/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    @yield('css')
    <script>
        function format_rupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);
    
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
    
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : rupiah ? prefix + rupiah : '';
        }
        window.APP = <?php echo json_encode([
                            'currency_symbol' => config('settings.currency_symbol'),
                            'warning_quantity' => config('settings.warning_quantity'),
                        ]) ?>
    </script>
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        @include('layouts.partials.navbar')
        @include('layouts.partials.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>@yield('content-header')</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            @yield('content-actions')
                        </div><!-- /.col -->
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                @include('layouts.partials.alert.success')
                @include('layouts.partials.alert.error')
                @yield('content')
            </section>

        </div>
        <!-- /.content-wrapper -->

        @include('layouts.partials.footer')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
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
    <!-- ./wrapper -->
    <script src="
    https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js
    "></script>
    <script>
        var date_system_value = "{{ 
            dateformat_custom()
         }}";
        var date_system = {{ config('settings.date_system') }};
        setInterval(function() {
                // if(date_system_value == 0){
                    // var date_time = moment().format('dddd, D MMMM YYYY, h:mm:ss a');
                // } else {
                    const currentTime = new Date(date_system_value);

                    var date_time = moment(currentTime).format('dddd, D MMMM YYYY, h:mm:ss a');
                // }
                document.getElementById('date_time').innerHTML = date_time;
            }, 1000);

            var url = window.location.pathname;
            if (url != '/admin/cart' || url != '/admin/purchase'){
                localStorage.removeItem('cart');
                localStorage.removeItem('cart_purchase');
            }
    </script>

    @yield('js')
</body>

</html>
