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

        $tags        = $api->get('/admin/analytics/tags')['data'] ?? [];
        $serviceTags = $api->get('/service-tags')['data'] ?? [];
        $tagLabels   = collect($serviceTags)->keyBy('id')->map(fn($t) => $t['label'])->toArray();

        $trendingTags = collect($tags)->take(6)->map(fn($t) => [
            'label'    => $tagLabels[$t['tag']] ?? $t['tag'],
            'jobCount' => $t['jobCount'],
        ])->values()->toArray();

        return view('admin.analytics', compact('overview', 'usersStats', 'trendingTags'));
    }
}
