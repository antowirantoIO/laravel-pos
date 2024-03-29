<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="{{route('home')}}" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    {{--
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
    --}}

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto" style="
        display: flex;
        align-items: center;
        gap: 1rem;
    ">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item">
		<strong>
      <span id="date_time"></span>
    </strong></li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">{{
            Session::get('expired_product')
          }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">{{
            Session::get('expired_product_list_lenght');
          }} Barang Expired Hari Ini</span>
          <div class="dropdown-divider"></div>
          @foreach (Session::get('expired_product_list') ?? [] as $item)
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> {{ $item->name }}
            <!-- <span class="float-right text-muted text-sm">3 mins</span> -->
          </a>
          <div class="dropdown-divider"></div>
          @endforeach
          <!-- <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a> -->
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
