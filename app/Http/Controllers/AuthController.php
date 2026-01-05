<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $users = [
        [
            'id' => '1',
            'name' => 'Super Admin',
            'email' => 'admin@notary.com',
            'role' => 'superadmin',
            'password' => 'admin123'
        ],
        [
            'id' => '2',
            'name' => 'Staff Member',
            'email' => 'staff@notary.com',
            'role' => 'staff',
            'password' => 'staff123'
        ],
        [
            'id' => '3',
            'name' => 'John Doe',
            'email' => 'john@notary.com',
            'role' => 'staff',
            'password' => 'john123'
        ],
    ];

    public function showLogin()
    {
        if (session('user')) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = collect($this->users)->firstWhere(function ($u) use ($request) {
            return $u['email'] === $request->email && $u['password'] === $request->password;
        });

        if ($user) {
            session(['user' => $user]);
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Email atau password salah');
    }

    public function logout()
    {
        session()->forget('user');
        return redirect()->route('login');
    }
}

