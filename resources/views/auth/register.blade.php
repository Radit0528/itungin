@extends('layouts.auth')

@section('title', 'Register - Itungin')

@section('content')
    <div class="form-container py-4">
        <h2 class="fw-normal mb-1" style="color: #111827; letter-spacing: -0.5px;">Create an Account</h2>
        <p class="mb-4" style="color: #6b7280; font-size: 0.95rem;">Start your transformational financial journey.</p>

        <div class="auth-toggle mb-4">
            <a href="{{ route('login') }}" class="btn btn-inactive text-decoration-none">Login</a>
            <a href="{{ route('register') }}" class="btn btn-active text-decoration-none">Sign Up</a>
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
                    <input type="text" name="name" class="form-control form-control-custom" placeholder="fullname" value="{{ old('name') }}" required autofocus>
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
                    <input type="text" name="username" class="form-control form-control-custom" placeholder="username" value="{{ old('username') }}" required>
                </div>
                @error('username')
                <div class="text-danger mt-1" style="font-size: 0.75rem; font-weight: 600;">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label-custom d-block">EMAIL ADDRESS</label>
                <div class="input-group input-group-custom">
                    <span class="input-group-text input-group-text-custom fw-bold fs-5">@</span>
                    <input type="email" name="email" class="form-control form-control-custom" placeholder="name@gmail.com" value="{{ old('email') }}" required>
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

        </form>
    </div>

    <div class="bottom-footer d-none d-xl-block position-relative mt-auto pt-4 pb-2" style="bottom: 0;">
        <span>© 2024 ITUNGIN FINANCIAL</span>
        <span>SUPPORT</span>
        <span>API</span>
    </div>
@endsection