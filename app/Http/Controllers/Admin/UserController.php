<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $raw   = app(ApiService::class)->get('/admin/users')['data'] ?? [];
        $users = [];

        foreach (array_values($raw) as $i => $u) {
            $num      = $i + 1;
            $isSeeker = ($u['type'] === 'seeker');
            $name     = $isSeeker
                ? trim(($u['firstName'] ?? '') . ' ' . ($u['lastName'] ?? ''))
                : ($u['fullName'] ?? 'N/A');
            $verified   = $u['isVerified'] ?? false;
            $hasProfile = $u['isProfileSet'] ?? false;

            $users[$num] = [
                'id'               => $num,
                'uid'              => $u['id'],
                'code'             => ($isSeeker ? 'SKR' : 'EMP') . '-' . strtoupper(substr($u['id'], 0, 8)),
                'name'             => $name ?: 'N/A',
                'email'            => $u['email'] ?? 'N/A',
                'phone'            => $u['mobileNumber'] ?? 'N/A',
                'joined'           => isset($u['createdAt']) ? date('F d, Y', strtotime($u['createdAt'])) : 'N/A',
                'dob'              => $isSeeker ? ($u['birthDate'] ?? 'N/A') : 'N/A (Corporate)',
                'type'             => $isSeeker ? 'Seeker' : 'Employer',
                'status'           => $verified ? 'Verified' : ($hasProfile ? 'Pending' : 'Incomplete'),
                'rating'           => '0.0',
                'district'         => $u['location'] ?? 'N/A',
                'address'          => $u['address'] ?? 'N/A',
                'img'              => $u['profilePhotoUrl'] ?? 'https://via.placeholder.com/150',
                'suspensionReason' => '',
                'needs'            => $verified ? 'Complete' : 'Valid ID, Clearance',
            ];
        }

        return view('admin.users', compact('users'));
    }

    public function photo(string $uid)
    {
        $base64 = app(ApiService::class)->post('/profile-photo', ['uid' => $uid])['data'] ?? null;
        return response()->json(['data' => $base64]);
    }
}
