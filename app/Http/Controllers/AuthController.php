<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Memproses data Login
    public function login(Request $request)
    {
        // Ubah validasi dari email menjadi username
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        // Auth::attempt akan otomatis mencari kolom 'username' di database
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    public function register(Request $request)
    {
        // 1. Tambahkan validasi untuk email
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // Validasi email ditambahkan
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. Masukkan request email ke proses pembuatan User
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email, // Simpan email ke database
            'password' => Hash::make($request->password),
        ]);

        // Login otomatis
        Auth::login($user);

        return redirect('/login');
    }

    // Memproses Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}