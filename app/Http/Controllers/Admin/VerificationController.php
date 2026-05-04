<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index()
    {
        $raw     = app(ApiService::class)->get('/admin/pendingVerifications')['data'] ?? [];
        $pending = [];

        foreach (array_values($raw) as $item) {
            $name = $item['fullName']
                ?? trim(($item['firstName'] ?? '') . ' ' . ($item['lastName'] ?? ''))
                ?: 'N/A';

            $pending[] = [
                'uid'          => $item['uid'],
                'name'         => $name,
                'type'         => $item['userType'] ?? 'seeker',
                'submittedAt'  => $item['updatedAt'] ?? null,
                'validIdUrl'   => $item['validIdUrl'] ?? null,
                'clearanceUrl' => $item['clearanceUrl'] ?? null,
            ];
        }

        return view('admin.verifications', ['pending' => $pending]);
    }

    public function verify(string $type, string $uid)
    {
        app(ApiService::class)->post('/admin/verifyUser', [
            'type'    => rtrim($type, 's'),
            'userUid' => $uid,
        ]);

        return response()->json(['success' => true]);
    }

    public function reject(string $type, string $uid, Request $request)
    {
        app(ApiService::class)->post('/admin/rejectVerification', [
            'type'    => rtrim($type, 's'),
            'userUid' => $uid,
        ]);

        return response()->json(['success' => true]);
    }
}
