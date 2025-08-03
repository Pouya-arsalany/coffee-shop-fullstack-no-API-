<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('panel.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        Auth::login($user);

        return redirect()->route('menu.index')->with('success', 'welcome !');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|exists:users,phone',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            Auth::login($user);
            return redirect()->route('menu.index')->with('success', 'Success!');
        }

        return back()->withErrors(['phone' => 'wrong phone number']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('menu.index')->with('success', 'Success!');
    }

    public function adminDashboard()
    {
        return view('panel.admin');
    }
}
