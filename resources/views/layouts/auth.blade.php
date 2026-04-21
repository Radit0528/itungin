<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Itungin')</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; }
        
        /* Panel Kiri */
        .bg-itungin-blue { background-color: #0d52c6; }
        .left-panel { padding: 3.5rem; }
        .hero-image { width: 100%; height: 380px; object-fit: cover; border-radius: 20px; }

        /* Panel Kanan */
        .right-panel { padding: 2rem; position: relative; }
        .form-container { max-width: 440px; width: 100%; margin: 0 auto; }
        
        /* Toggle Switch */
        .auth-toggle { background-color: #f3f4f6; border-radius: 50px; padding: 4px; display: flex; }
        .auth-toggle .btn { border-radius: 50px; font-size: 0.9rem; font-weight: 600; padding: 8px 0; flex: 1; border: none; }
        .auth-toggle .btn-active { background-color: #ffffff; color: #111827; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .auth-toggle .btn-inactive { background-color: transparent; color: #6b7280; }
        .auth-toggle .btn-inactive:hover { color: #374151; }

        /* Input & Buttons */
        .btn-social { background-color: #ffffff; border: 1px solid #e5e7eb; color: #374151; font-weight: 600; font-size: 0.9rem; border-radius: 24px; padding: 10px; transition: all 0.2s; }
        .btn-social:hover { background-color: #f9fafb; }
        .input-group-custom { background-color: #f3f4f6; border-radius: 12px; overflow: hidden; border: 1px solid transparent; transition: border-color 0.2s; }
        .input-group-custom:focus-within { border-color: #0d52c6; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(13, 82, 198, 0.1); }
        .input-group-text-custom { background-color: transparent; border: none; color: #9ca3af; padding-right: 0; padding-left: 1rem; }
        .form-control-custom { background-color: transparent; border: none; color: #111827; padding: 12px 16px; font-size: 0.95rem; font-weight: 500; }
        .form-control-custom:focus { box-shadow: none; background-color: transparent; }
        .form-control-custom::placeholder { color: #9ca3af; font-weight: 400; }
        .form-label-custom { font-size: 0.7rem; font-weight: 700; color: #111827; letter-spacing: 0.5px; margin-bottom: 0.4rem; }
        .btn-primary-custom { background-color: #0d52c6; color: white; border-radius: 24px; padding: 12px; font-weight: 600; font-size: 0.95rem; transition: background-color 0.2s; border: none; }
        .btn-primary-custom:hover { background-color: #0a42a0; color: white; }
        
        /* Text & Footer */
        .divider-text { color: #9ca3af; font-size: 0.7rem; font-weight: 700; letter-spacing: 1px; }
        .bottom-footer { position: absolute; bottom: 2rem; width: 100%; text-align: center; font-size: 0.7rem; color: #6b7280; font-weight: 600; left: 0; word-spacing: 15px; }
    </style>
</head>
<body>
    <div class="container-fluid p-0 min-vh-100">
        <div class="row g-0 min-vh-100">

            <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-between bg-itungin-blue text-white left-panel">
                <div>
                    <h4 class="fw-bold m-0" style="letter-spacing: -0.5px;">itungin</h4>
                </div>

                <div class="mb-auto mt-4 pe-4">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop" alt="Skyscraper" class="hero-image mb-5 shadow">

                    <h1 class="fw-bold mb-3" style="font-size: 3rem; line-height: 1.1; letter-spacing: -1px;">
                        Master your capital with<br>Editorial Intelligence.
                    </h1>
                    <p class="mb-5" style="font-size: 1.15rem; color: #a1c2ff; max-width: 90%;">
                        Elevate your financial journey from transactional to transformational. Experience the digital private bank built for clarity.
                    </p>
                </div>
            </div>

            <div class="col-lg-6 right-panel d-flex flex-column align-items-center justify-content-center bg-white overflow-auto py-5">
                
                @yield('content')

            </div>

        </div>
    </div>
</body>
</html>