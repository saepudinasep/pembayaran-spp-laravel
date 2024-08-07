<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-text mx-3">Pembayaran SPP</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item @if (Route::currentRouteName() == 'dashboard.index') active @endif">
        <a class="nav-link" href="{{ route('dashboard.index') }}">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>

    @if (Auth::user()->admin)
        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
            Interface
        </div>
        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item @if (Route::is('spp.index', 'admin.index', 'staff.index', 'student.index')) active @endif">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-database"></i>
                <span>Master Data</span>
            </a>
            <div id="collapseTwo" class="collapse @if (Route::is('spp.index', 'admin.index', 'staff.index', 'student.index')) show @endif"
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">master data</h6>
                    <a class="collapse-item @if (Route::currentRouteName() == 'spp.index') active @endif"
                        href="{{ route('spp.index') }}">SPP</a>
                    <a class="collapse-item @if (Route::currentRouteName() == 'admin.index') active @endif"
                        href="{{ route('admin.index') }}">Admin</a>
                    <a class="collapse-item @if (Route::currentRouteName() == 'staff.index') active @endif"
                        href="{{ route('staff.index') }}">Staff</a>
                    <a class="collapse-item @if (Route::currentRouteName() == 'student.index') active @endif"
                        href="{{ route('student.index') }}">Student</a>
                </div>
            </div>
        </li>
    @endif


    @if (Auth::user()->staff || Auth::user()->admin)
        <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-money-check-alt"></i>
                <span>Transaksi</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-file-export"></i>
                <span>Laporan</span>
            </a>
        </li>
    @elseif (Auth::user()->student)
        <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-history"></i>
                <span>History</span>
            </a>
        </li>
    @endif

</ul>
