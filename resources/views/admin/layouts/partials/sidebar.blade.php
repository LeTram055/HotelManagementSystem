<!-- <div id="overlay"></div>
<nav class="sidebar" id="sidebarMenu">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>&nbsp;
                    Tổng quan
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/account*') ? 'active' : '' }}"
                    href="{{ route('admin.account.index') }}">
                    <i class="fa-solid fa-file-invoice"></i>&nbsp;
                    Tài khoản
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/customer*') ? 'active' : '' }} "
                    href="{{ route('admin.customer.index') }}">
                    <i class="fa-solid fa-address-book"></i>&nbsp;
                    Khách hàng
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/employee*') ? 'active' : '' }} "
                    href="{{ route('admin.employee.index') }}">
                    <i class="fa-solid fa-users"></i>&nbsp;
                    Nhân viên
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/roomstatus*') ? 'active' : '' }}"
                    href="{{ route('admin.roomstatus.index') }}">
                    <i class="fa-solid fa-sliders"></i>&nbsp;
                    Trạng thái phòng
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/facility*') ? 'active' : '' }}"
                    href="{{ route('admin.facility.index') }}">
                    <i class="fa-solid fa-door-closed"></i>&nbsp;
                    Thiết bị
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/typeimage*') ? 'active' : '' }}"
                    href="{{ route('admin.typeimage.index') }}">
                    <i class="fa-solid fa-image"></i>&nbsp;
                    Ảnh loại phòng
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/type*') && !request()->is('admin/typeimage*')? 'active' : '' }}"
                    href="{{ route('admin.type.index') }}">
                    <i class="fa-solid fa-list"></i>&nbsp;
                    Loại phòng
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/room*') && !request()->is('admin/roomstatus*')? 'active' : '' }}"
                    href="{{ route('admin.room.index') }}">
                    <i class="fa-solid fa-door-closed"></i>&nbsp;
                    Phòng
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/reservation*') ? 'active' : '' }}"
                    href="{{ route('admin.reservation.index') }}">
                    <i class="fa-solid fa-calendar"></i>&nbsp;
                    Đơn đặt phòng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/payment*') ? 'active' : '' }}"
                    href="{{ route('admin.payment.index') }}">
                    <i class="fa-solid fa-money-check-alt"></i>&nbsp;
                    Thanh toán
                </a>
            </li>



        </ul>
    </div>
</nav> -->

<div id="overlay"></div>
<nav class="sidebar" id="sidebarMenu">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">

            {{-- Kiểm tra người dùng đã đăng nhập chưa --}}
            @if(Auth::check())
            {{-- Hiển thị cho Admin --}}
            @if(Auth::user()->account_role === 'admin')
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>&nbsp;
                    Tổng quan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/account*') ? 'active' : '' }}"
                    href="{{ route('admin.account.index') }}">
                    <i class="fa-solid fa-file-invoice"></i>&nbsp;
                    Tài khoản
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/customer*') ? 'active' : '' }}"
                    href="{{ route('admin.customer.index') }}">
                    <i class="fa-solid fa-address-book"></i>&nbsp;
                    Khách hàng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/employee*') ? 'active' : '' }}"
                    href="{{ route('admin.employee.index') }}">
                    <i class="fa-solid fa-users"></i>&nbsp;
                    Nhân viên
                </a>
            </li>
            @endif

            {{-- Hiển thị chung cho Admin và Employee --}}
            @if(in_array(Auth::user()->account_role, ['admin', 'employee']))
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>&nbsp;
                    Tổng quan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/room*') && !request()->is('admin/roomstatus*') ? 'active' : '' }}"
                    href="{{ route('admin.room.index') }}">
                    <i class="fa-solid fa-door-closed"></i>&nbsp;
                    Phòng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/reservation*') ? 'active' : '' }}"
                    href="{{ route('admin.reservation.index') }}">
                    <i class="fa-solid fa-calendar"></i>&nbsp;
                    Đơn đặt phòng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/payment*') ? 'active' : '' }}"
                    href="{{ route('admin.payment.index') }}">
                    <i class="fa-solid fa-money-check-alt"></i>&nbsp;
                    Thanh toán
                </a>
            </li>
            @endif
            @else
            {{-- Người dùng chưa đăng nhập --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">
                    <i class="fa-solid fa-sign-in-alt"></i>&nbsp;
                    Đăng nhập
                </a>
            </li>
            @endif

        </ul>
    </div>
</nav>