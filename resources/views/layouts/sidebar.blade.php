<aside class="sidebar">
    <a href="#" class="brand-logo">
        <div class="brand-icon"><i class="bi bi-wallet2"></i></div>
        <div>
            <h5 class="m-0 fw-bold" style="letter-spacing: -0.5px;">Itungin</h5>
            <small style="font-size: 0.6rem; color: #9ca3af; letter-spacing: 1px; font-weight: 600;">PRIVATE BANKING</small>
        </div>
    </a>

    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('transaksi') }}" class="nav-link {{ Request::is('transaksi') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Transaksi
            </a>
        </li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-bullseye"></i> Targetku</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-robot"></i> AI Assistant</a></li>
    </ul>

    <div class="sidebar-bottom">
        <ul class="nav-menu">
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-question-circle"></i> Support</a></li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit(); return false;">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </form>
            </li>
        </ul>
    </div>
</aside>