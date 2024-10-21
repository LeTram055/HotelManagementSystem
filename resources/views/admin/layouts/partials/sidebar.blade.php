<div id="overlay"></div>
<nav class="sidebar" id="sidebarMenu">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/account*') ? 'active' : '' }}"
                    href="{{ route('admin.account.index') }}">
                    <i class="fa-solid fa-file-invoice"></i>&nbsp;
                    Account
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/customer*') ? 'active' : '' }} "
                    href="{{ route('admin.customer.index') }}">
                    <i class="fa-solid fa-address-book"></i>&nbsp;
                    Customer
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/employee*') ? 'active' : '' }} "
                    href="{{ route('admin.employee.index') }}">
                    <i class="fa-solid fa-users"></i>&nbsp;
                    Employee
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/roomstatus*') ? 'active' : '' }}"
                    href="{{ route('admin.roomstatus.index') }}">
                    <i class="fa-solid fa-sliders"></i>&nbsp;
                    Room Status
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/facility*') ? 'active' : '' }}"
                    href="{{ route('admin.facility.index') }}">
                    <i class="fa-solid fa-door-closed"></i>&nbsp;
                    Facility
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/typeimage*') ? 'active' : '' }}"
                    href="{{ route('admin.typeimage.index') }}">
                    <i class="fa-solid fa-image"></i>&nbsp;
                    Type Image
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/type*') && !request()->is('admin/typeimage*')? 'active' : '' }}"
                    href="{{ route('admin.type.index') }}">
                    <i class="fa-solid fa-list"></i>&nbsp;
                    Type
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/room*') && !request()->is('admin/roomstatus*')? 'active' : '' }}"
                    href="{{ route('admin.room.index') }}">
                    <i class="fa-solid fa-door-closed"></i>&nbsp;
                    Room
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/reservation*') ? 'active' : '' }}"
                    href="{{ route('admin.reservation.index') }}">
                    <i class="fa-solid fa-calendar"></i>&nbsp;
                    Reservation
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/payment*') ? 'active' : '' }}"
                    href="{{ route('admin.payment.index') }}">
                    <i class="fa-solid fa-money-check-alt"></i>&nbsp;
                    Payment
                </a>
            </li>



        </ul>
    </div>
</nav>