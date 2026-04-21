<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Itungin')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* =========================================================
           CSS UTAMA APP (Sidebar, Layout, Dashboard)
           ========================================================= */
        body { font-family: 'Inter', sans-serif; background-color: #f8faff; color: #111827; }
        .sidebar { width: 260px; background-color: #ffffff; border-right: 1px solid #f3f4f6; position: fixed; height: 100vh; display: flex; flex-direction: column; padding: 1.5rem; z-index: 1000; }
        .brand-logo { display: flex; align-items: center; margin-bottom: 2.5rem; text-decoration: none; color: #0d52c6; }
        .brand-icon { background-color: #0d52c6; color: white; border-radius: 8px; padding: 6px 10px; margin-right: 10px; font-size: 1.2rem; }
        .nav-menu { list-style: none; padding: 0; margin: 0; }
        .nav-item { margin-bottom: 0.5rem; }
        .nav-link { display: flex; align-items: center; padding: 12px 16px; color: #6b7280; border-radius: 12px; text-decoration: none; font-weight: 500; transition: all 0.2s; }
        .nav-link i { font-size: 1.2rem; margin-right: 12px; }
        .nav-link:hover { background-color: #f3f4f6; color: #111827; }
        .nav-link.active { background-color: #0d52c6; color: #ffffff; }
        .sidebar-bottom { margin-top: auto; }
        
        .main-content { margin-left: 260px; padding: 2.5rem 3rem; }
        h1.page-title { font-size: 2rem; font-weight: 700; letter-spacing: -0.5px; margin-bottom: 0.2rem; }
        .page-subtitle { color: #6b7280; font-size: 0.95rem; }
        
        .custom-card { background-color: #ffffff; border-radius: 20px; border: none; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); padding: 1.5rem; height: 100%; }
        .card-wealth { background-color: #0d52c6; color: #ffffff; padding: 2rem; position: relative; overflow: hidden; }
        .wealth-amount { font-size: 2.8rem; font-weight: 700; letter-spacing: -1px; margin-top: 0.5rem; margin-bottom: 0; }
        .badge-trend { background-color: rgba(255, 255, 255, 0.2); color: #ffffff; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; backdrop-filter: blur(4px); }
        .mini-card-title { font-size: 0.7rem; color: #9ca3af; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 0.2rem; }
        .mini-card-amount { font-size: 1.4rem; font-weight: 700; color: #111827; margin: 0; }
        .icon-box { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .bg-light-green { background-color: #d1fae5; color: #059669; }
        .bg-light-red { background-color: #fee2e2; color: #dc2626; }
        .text-green { color: #059669; font-weight: 700; font-size: 0.9rem; }
        .text-red { color: #dc2626; font-weight: 700; font-size: 0.9rem; }
        .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .toggle-btn-group { background-color: #f3f4f6; border-radius: 20px; padding: 4px; display: flex; }
        .toggle-btn { border: none; background: transparent; padding: 4px 16px; border-radius: 16px; font-size: 0.85rem; font-weight: 600; color: #6b7280; }
        .toggle-btn.active { background-color: #ffffff; color: #0d52c6; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .activity-item { display: flex; align-items: center; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
        .activity-item:last-child { border-bottom: none; }
        .activity-icon { width: 40px; height: 40px; border-radius: 50%; background-color: #f0f4ff; color: #0d52c6; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-size: 1.1rem; }
        .activity-details h6 { margin: 0; font-size: 0.95rem; font-weight: 600; color: #111827; }
        .activity-details small { color: #9ca3af; font-size: 0.75rem; }
        .activity-amount { margin-left: auto; font-weight: 700; text-align: right; }
        .dashboard-footer { margin-top: 4rem; text-align: center; font-size: 0.8rem; color: #9ca3af; }
        .dashboard-footer a { color: #6b7280; text-decoration: none; margin: 0 10px; }
        .dashboard-footer a:hover { color: #111827; }

        /* =========================================================
           CSS TAMBAHAN DARI TRANSAKSI & MODAL
           ========================================================= */
        .summary-card { border: 1px solid #f3f4f6; border-radius: 20px; padding: 1.5rem 2rem; background-color: #ffffff; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01); }
        .summary-title { font-size: 0.85rem; color: #6b7280; font-weight: 600; }
        .text-success-custom { color: #059669 !important; }
        .text-danger-custom { color: #dc2626 !important; }
        .bg-success-light { background-color: #d1fae5; }
        .bg-danger-light { background-color: #fee2e2; }

        .btn-primary-custom { background-color: #0d52c6; color: #ffffff; border: none; transition: background-color 0.2s; }
        .btn-primary-custom:hover { background-color: #0a42a0; color: #ffffff; }

        .filter-toggle { background-color: #f3f4f6; border-radius: 50px; padding: 4px; display: inline-flex; }
        .filter-toggle .btn { border-radius: 50px; font-size: 0.85rem; font-weight: 600; padding: 6px 20px; color: #6b7280; border: none; }
        .filter-toggle .btn.active { background-color: #ffffff; color: #111827; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }

        .transaction-list-container { background-color: #ffffff; border-radius: 20px; padding: 2rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); }
        .transaction-item { display: flex; align-items: center; padding: 1.25rem; border: 1px solid #f3f4f6; border-radius: 16px; margin-bottom: 1rem; transition: all 0.2s; }
        .transaction-item:hover { border-color: #e5e7eb; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02); }
        .transaction-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-right: 1.25rem; }

        .custom-modal-content { border-radius: 24px; border: none; padding: 1rem; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1); }
        .modal-header-custom { border-bottom: none; padding-bottom: 0; }
        .btn-close-custom { background-size: 0.8rem; opacity: 0.5; }

        .type-toggle { background-color: #f3f4f6; border-radius: 12px; padding: 4px; display: flex; }
        .type-toggle .btn { border-radius: 10px; font-size: 0.9rem; font-weight: 600; padding: 8px 0; flex: 1; color: #6b7280; border: none; background: transparent; }
        .type-toggle .btn.active { background-color: #ffffff; color: #111827; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }

        .form-control-modal { background-color: #f8faff; border: 1px solid transparent; color: #111827; padding: 12px 16px; font-size: 0.95rem; border-radius: 12px; font-weight: 500; }
        .form-control-modal:focus { box-shadow: 0 0 0 4px rgba(13, 82, 198, 0.1); border-color: #0d52c6; background-color: #ffffff; }
        
        .btn-modal-action { border-radius: 50px; padding: 12px; font-weight: 600; font-size: 0.95rem; border: none; }
        .btn-light-custom { background-color: #eef2ff; color: #4f46e5; transition: all 0.2s; }
        .btn-light-custom:hover { background-color: #e0e7ff; }
    </style>

    @stack('styles')
</head>
<body>

    @include('layouts.sidebar')

    <main class="main-content">
        @include('layouts.header')

        @yield('content')
    
        @include('layouts.footer')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>