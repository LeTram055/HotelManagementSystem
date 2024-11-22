<nav class="navbar navbar-dark fixed-top flex-md-nowrap p-1 shadow navbar-bg">
    <div>
        <button class="navbar-toggler d-md-none" type="button" id="sidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="">
            <img src="{{ asset('images/logo_nbg.png') }}" alt="Ánh Dương Hotel" style="height: 40px;">
            Ánh Dương Hotel
        </a>

    </div>

    <!-- <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Ánh Dương Hotel</a> -->
    <ul class="navbar-nav px-3 ml-auto">
        @if(Auth::check())
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Xin chào, {{ Auth::user()->account_username }}
            </a>

            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('password.change') }}">Đổi mật khẩu</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item">Đăng xuất</button>
                    </form>
                </li>
            </ul>
        </li>
        <!-- Kiểm tra người dùng đã đăng nhập hay chưa -->
        <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->account_username }}
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('password.change') }}">Đổi mật khẩu</a></li>
                
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item">Sign out</button>
                    </form>
                </li>
            </ul>
        </li> -->
        @else
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
        </li>
        @endif
    </ul>
</nav>

<!--  d-md-none -->