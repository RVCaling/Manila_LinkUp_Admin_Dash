<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kreait\Laravel\Firebase\Facades\Firebase;

class AdminDashboardController extends Controller
{
    public function showLogin()
    {
        if (Session::has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate(['id_token' => 'required|string']);

        try {
            $verified = Firebase::auth()->verifyIdToken($request->id_token, false, 60);
            $uid      = $verified->claims()->get('sub');
            $email    = $verified->claims()->get('email');
            $name     = $verified->claims()->get('name') ?? '';
        } catch (\Throwable $e) {
            return back()->with('error', 'Authentication failed. Please try again.');
        }

        $isAdmin = Firebase::firestore()->database()
            ->collection('admins')
            ->document($uid)
            ->snapshot()
            ->exists();

        if (!$isAdmin) {
            return back()->with('error', 'Access denied. This account is not an admin.');
        }

        Session::put([
            'admin_logged_in'    => true,
            'admin_uid'          => $uid,
            'admin_email'        => $email,
            'admin_name'         => $name,
            'admin_id_token'     => $request->id_token,
            'admin_refresh_token'=> $request->refresh_token,
            'admin_token_expiry' => now()->addMinutes(55)->timestamp,
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function index(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $api      = app(ApiService::class);
        $users    = $api->get('/admin/analytics/users')['data']    ?? [];
        $overview = $api->get('/admin/analytics/overview')['data'] ?? [];
        $growth   = $api->get('/admin/analytics/users/growth')['data'] ?? [];
        $pending  = $api->get('/admin/pendingVerifications')['data'] ?? [];

        $dashboardData = [
            'totalUsers'          => ($users['totalSeekers']    ?? 0) + ($users['totalEmployers']    ?? 0),
            'totalSeekers'        => $users['totalSeekers']    ?? 0,
            'totalEmployers'      => $users['totalEmployers']  ?? 0,
            'verifiedUsers'       => ($users['verifiedSeekers'] ?? 0) + ($users['verifiedEmployers'] ?? 0),
            'newUsersLast30Days'  => ($users['newSeekers']      ?? 0) + ($users['newEmployers']      ?? 0),
            'activeJobs'          => $overview['activeJobs']   ?? 0,
            'pendingVerifications'=> count(array_values($pending)),
            'growth'              => $growth,
        ];

        return view('admin.dashboard', compact('dashboardData'));
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('admin.login');
    }
}
