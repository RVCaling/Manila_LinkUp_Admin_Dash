<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiService;

class AnalyticsController extends Controller
{
    public function index()
    {
        $api        = app(ApiService::class);
        $overview   = $api->get('/admin/analytics/overview')['data'] ?? [];
        $usersStats = $api->get('/admin/analytics/users')['data'] ?? [];

        return view('admin.analytics', compact('overview', 'usersStats'));
    }
}
