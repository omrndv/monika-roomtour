<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $admin_user = 'admin';
        $admin_pass = 'adminmonikart';

        if ($request->username == $admin_user && $request->password == $admin_pass) {
            session(['admin_logged_in' => true]);
            
            return redirect()->route('dashboard')
                ->with('success', 'Selamat datang kembali, Bos! Siap pantau cuan hari ini?');
        }

        return back()->withErrors(['auth_error' => 'Username atau Password salah!']);
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('login');
    }
}