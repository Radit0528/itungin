<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Itungin')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* CSS yang panjang tetap di sini, disamakan dengan jawaban sebelumnya */
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
        
        /* ... sisa CSS ... */
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
    </style>
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