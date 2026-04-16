<div class="d-flex justify-content-between align-items-center mb-4">
    @if (Request::is('dashboard'))
        <div>
            <h1 class="page-title">Welcome back, {{ Auth::user()->name ?? 'Adrian' }}</h1>
            <p class="page-subtitle">Your financial intelligence report is ready.</p>
        </div>
        <div class="dropdown">
            <a href="#" class="d-block link-dark text-decoration-none" id="dropdownUser" data-bs-toggle="dropdown"
                aria-expanded="false">
                <div class="rounded-circle border border-2 border-white shadow-sm d-flex align-items-center justify-content-center"
                    style="width: 48px; height: 48px; background-color: #f3f4f6; color: #9ca3af;">
                    <i class="bi bi-person-fill fs-3"></i>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="dropdownUser">
                <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i> Profil</a></li>
                <li><a class="dropdown-item py-2" href="#"><i class="bi bi-gear me-2"></i> Pengaturan</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger"><i
                                class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    @endif
</div>
