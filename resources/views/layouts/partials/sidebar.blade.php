<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('home')}}" class="brand-link">
        <img src="{{ asset('images/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->getAvatar() }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->getFullname() }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">Dashboard</li>
                <li class="nav-item has-treeview">
                    <a href="{{route('home')}}" class="nav-link {{ activeSegment('admin', 1) }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-header">Management</li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('products.index') }}" class="nav-link {{ activeSegment('products') }}">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Products</p>
                    </a>
                </li>
				<li class="nav-item has-treeview">
                    <a href="{{ route('suppliers.index') }}" class="nav-link {{ activeSegment('suppliers') }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Suppliers</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('uom.index') }}" class="nav-link {{ activeSegment('uom') }}">
                        <i class="nav-icon fas fa-inbox"></i>
                        <p>UOM</p>
                    </a>
                </li>
                <li class="nav-header">POS</li>
				<li class="nav-item has-treeview">
                    <a href="{{ route('purchase.index') }}" class="nav-link {{ activeSegment('purchase') }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Purchase</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('cart.index') }}" class="nav-link {{ activeSegment('cart') }}">
                        <i class="nav-icon fas fa-barcode"></i>
                        <p>Open POS</p>
                    </a>
                </li>
                <li class="nav-header">Report</li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('orders.index') }}" class="nav-link {{ activeSegment('orders') }}">
                        <i class="nav-icon fas fa-cart-plus"></i>
                        <p>Sell Orders</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('orders.purchase') }}" class="nav-link {{ activeSegment('purchase_orders') }}">
                    <i class="nav-icon fas fa-cart-arrow-down"></i>
                        <p>Buy Orders</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('reports.index') }}" class="nav-link {{ activeSegment('reports') }}">
                    <i class="nav-icon fas fa-pie-chart"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                <li class="nav-header">
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('settings.index') }}" class="nav-link {{ activeSegment('settings') }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Settings</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit()">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                        <form action="{{route('logout')}}" method="POST" id="logout-form">
                            @csrf
                        </form>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
