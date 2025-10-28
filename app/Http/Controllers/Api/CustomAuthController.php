<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomAuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function login(Request $req)
    {
        $req->validate([
            'nik' => 'required',
            'password' => 'required'
        ]);
        if (Auth::attempt(['nik' => $req->nik, 'password' => $req->password])) {
            $req->session()->regenerate();
            return redirect()->intended(route('home'));
        }
        return back()->withErrors(['nik' => 'NIK atau password salah'])->withInput();
    }

    public function register(Request $req)
    {
        $req->validate([
            'name' => 'required|string|max:255',
            'nik' => ['required','digits_between:10,20', Rule::unique('users','nik')],
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name'=> $req->name,
            'nik'=> $req->nik,
            'email'=> $req->email,
            'password'=> Hash::make($req->password),
        ]);

        Auth::login($user);
        return redirect()->route('home');
    }

    public function logout(Request $req)
    {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect()->route('home');
    }
}