<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminDashboardController extends Controller
{
    // 1. Show the Login Page
    public function showLogin()
    {
        if (Session::has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    // 2. Handle Login Logic
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // DUMMY AUTH: Replace this logic when connecting to Firebase
        if ($request->email === 'admin@manilalinkup.ph' && $request->password === 'password123') {
            Session::put('admin_logged_in', true);
            Session::put('admin_email', $request->email);
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid credentials. Please try again.');
    }

    // 3. Show Dashboard (Protected)
    public function index(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $viewType = $request->query('type', 'seeker');
        $users = [
            [
                'id' => 101,
                'name' => 'Josh Wayne',
                'birthday' => 'May 12, 1995',
                'address' => 'Makati City, Metro Manila',
                'id_url' => 'https://i.imgur.com/8K59x7H.png',
                'clearance_url' => 'https://i.imgur.com/8K59x7H.png',
                'id_number' => '012700',
                'expiry' => '06/2028'
            ]
        ];

        return view('admin.dashboard', compact('users', 'viewType'));
    }

    // 4. Handle Logout
    public function logout()
    {
        Session::forget(['admin_logged_in', 'admin_email']);
        return redirect()->route('admin.login');
    }
}