<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Itungin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
        }

        /* Left Panel Styling */
        .bg-itungin-blue {
            background-color: #0d52c6;
        }

        .left-panel {
            padding: 3.5rem;
        }

        .hero-image {
            width: 100%;
            height: 380px;
            object-fit: cover;
            border-radius: 20px;
        }

        .trust-badge {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            backdrop-filter: blur(5px);
        }

        .avatar-group img {
            border: 2px solid #0d52c6;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-group img:nth-child(2) {
            margin-left: -12px;
        }

        /* Right Panel Styling */
        .right-panel {
            padding: 2rem;
            position: relative;
        }

        .form-container {
            max-width: 440px;
            width: 100%;
            margin: 0 auto;
        }

        /* Toggle Login/Signup */
        .auth-toggle {
            background-color: #f3f4f6;
            border-radius: 50px;
            padding: 4px;
            display: flex;
        }

        .auth-toggle .btn {
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 8px 0;
            flex: 1;
        }

        .auth-toggle .btn-active {
            background-color: #ffffff;
            color: #111827;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .auth-toggle .btn-inactive {
            color: #6b7280;
        }

        .auth-toggle .btn-inactive:hover {
            color: #374151;
        }

        /* Social Buttons */
        .btn-social {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            color: #374151;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 24px;
            padding: 10px;
            transition: all 0.2s;
        }

        .btn-social:hover {
            background-color: #f9fafb;
        }

        /* Form Inputs */
        .input-group-custom {
            background-color: #f3f4f6;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid transparent;
            transition: border-color 0.2s;
        }

        .input-group-custom:focus-within {
            border-color: #0d52c6;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(13, 82, 198, 0.1);
        }

        .input-group-text-custom {
            background-color: transparent;
            border: none;
            color: #9ca3af;
            padding-right: 0;
            padding-left: 1rem;
        }

        .form-control-custom {
            background-color: transparent;
            border: none;
            color: #111827;
            padding: 12px 16px;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .form-control-custom:focus {
            box-shadow: none;
            background-color: transparent;
        }

        .form-control-custom::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .form-label-custom {
            font-size: 0.7rem;
            font-weight: 700;
            color: #111827;
            letter-spacing: 0.5px;
            margin-bottom: 0.4rem;
        }

        /* Primary Button */
        .btn-primary-custom {
            background-color: #0d52c6;
            color: white;
            border-radius: 24px;
            padding: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background-color 0.2s;
        }

        .btn-primary-custom:hover {
            background-color: #0a42a0;
            color: white;
        }

        /* Miscellaneous */
        .divider-text {
            color: #9ca3af;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .bottom-footer {
            position: absolute;
            bottom: 2rem;
            width: 100%;
            text-align: center;
            font-size: 0.7rem;
            color: #6b7280;
            font-weight: 600;
            left: 0;
            word-spacing: 15px;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0 min-vh-100">
        <div class="row g-0 min-vh-100">

            <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-between bg-itungin-blue text-white left-panel">
                <div>
                    <h4 class="fw-bold m-0" style="letter-spacing: -0.5px;">Itungin</h4>
                </div>

                <div class="mb-auto mt-4 pe-4">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop" alt="Skyscraper" class="hero-image mb-5 shadow">

                    <h1 class="fw-bold mb-3" style="font-size: 3rem; line-height: 1.1; letter-spacing: -1px;">
                        Master your capital with<br>Editorial Intelligence.
                    </h1>
                    <p class="mb-5" style="font-size: 1.15rem; color: #a1c2ff; max-width: 90%;">
                        Elevate your financial journey from transactional to transformational. Experience the digital private bank built for clarity.
                    </p>

                    <div class="d-inline-flex align-items-center p-2 pe-4 trust-badge">
                        <div class="d-flex avatar-group me-3 ms-1">
                            <img src="https://i.pravatar.cc/100?img=1" alt="User 1">
                            <img src="https://i.pravatar.cc/100?img=33" alt="User 2">
                        </div>
                        <span style="font-size: 0.85rem; font-weight: 500;">Trusted by 15,000+ high-net-worth individuals</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 right-panel d-flex flex-column align-items-center justify-content-center bg-white overflow-auto">

                <div class="form-container py-4">
                    <h2 class="fw-normal mb-1" style="color: #111827; letter-spacing: -0.5px;">Create an Account</h2>
                    <p class="mb-4" style="color: #6b7280; font-size: 0.95rem;">Start your transformational financial journey.</p>

                    <div class="auth-toggle mb-4">
                        <a href="{{ route('login') }}" class="btn btn-inactive text-decoration-none">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-active text-decoration-none">Sign Up</a>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <button type="button" class="btn btn-social w-100 d-flex align-items-center justify-content-center">
                                <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="18" class="me-2" alt="Google"> Google
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-social w-100 d-flex align-items-center justify-content-center">
                                <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" width="18" class="me-2" alt="Facebook"> Facebook
                            </button>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4">
                        <hr class="flex-grow-1 m-0" style="border-color: #e5e7eb;">
                        <span class="px-3 divider-text">OR CONTINUE WITH</span>
                        <hr class="flex-grow-1 m-0" style="border-color: #e5e7eb;">
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label-custom d-block">FULL NAME</label>
                            <div class="input-group input-group-custom">
                                <span class="input-group-text input-group-text-custom">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    </svg>
                                </span>
                                <input type="text" name="name" class="form-control form-control-custom" placeholder="John Doe" value="{{ old('name') }}" required autofocus>
                            </div>
                            @error('name')
                            <div class="text-danger mt-1" style="font-size: 0.75rem; font-weight: 600;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom d-block">USERNAME</label>
                            <div class="input-group input-group-custom">
                                <span class="input-group-text input-group-text-custom">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                    </svg>
                                </span>
                                <input type="text" name="username" class="form-control form-control-custom" placeholder="johndoe123" value="{{ old('username') }}" required>
                            </div>
                            @error('username')
                            <div class="text-danger mt-1" style="font-size: 0.75rem; font-weight: 600;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom d-block">EMAIL ADDRESS</label>
                            <div class="input-group input-group-custom">
                                <span class="input-group-text input-group-text-custom fw-bold fs-5">@</span>
                                <input type="email" name="email" class="form-control form-control-custom" placeholder="name@company.com" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                            <div class="text-danger mt-1" style="font-size: 0.75rem; font-weight: 600;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom d-block">PASSWORD</label>
                            <div class="input-group input-group-custom">
                                <span class="input-group-text input-group-text-custom">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z" />
                                    </svg>
                                </span>
                                <input type="password" name="password" class="form-control form-control-custom" placeholder="••••••••" required>
                                <span class="input-group-text input-group-text-custom pe-3" style="cursor: pointer;">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                    </svg>
                                </span>
                            </div>
                            @error('password')
                            <div class="text-danger mt-1" style="font-size: 0.75rem; font-weight: 600;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom d-block">CONFIRM PASSWORD</label>
                            <div class="input-group input-group-custom">
                                <span class="input-group-text input-group-text-custom">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z" />
                                    </svg>
                                </span>
                                <input type="password" name="password_confirmation" class="form-control form-control-custom" placeholder="••••••••" required>
                                <span class="input-group-text input-group-text-custom pe-3" style="cursor: pointer;">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 btn-primary-custom mb-4 mt-2">Sign Up for Itungin</button>

                        <p class="text-center" style="font-size: 0.75rem; color: #6b7280; max-width: 90%; margin: 0 auto;">
                            By continuing, you agree to Itungin's <a href="#" class="text-dark fw-bold text-decoration-none">Terms of Service</a> and <a href="#" class="text-dark fw-bold text-decoration-none">Privacy Policy</a>.
                        </p>
                    </form>
                </div>

                <div class="bottom-footer d-none d-xl-block position-relative mt-auto pt-4 pb-2" style="bottom: 0;">
                    <span>© 2024 ITUNGIN FINANCIAL</span>
                    <span>SUPPORT</span>
                    <span>API</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>