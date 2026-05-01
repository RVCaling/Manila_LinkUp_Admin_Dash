<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
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
