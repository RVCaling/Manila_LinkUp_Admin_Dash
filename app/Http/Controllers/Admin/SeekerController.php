<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiService;

class SeekerController extends Controller
{
    public function index()
    {
        $raw     = app(ApiService::class)->get('/admin/seekers', ['verified' => 'false'])['data'] ?? [];
        $seekers = [];
        $modal   = [];

        foreach (array_values($raw) as $i => $s) {
            $num  = $i + 1;
            $name = trim(($s['firstName'] ?? '') . ' ' . ($s['lastName'] ?? ''));
            $hasId        = !empty($s['validIdUrl']);
            $hasClearance = !empty($s['clearanceUrl']);
            $docNames     = implode(', ', array_filter([
                $hasId        ? 'Valid ID'      : null,
                $hasClearance ? 'NBI Clearance' : null,
            ])) ?: 'None uploaded';

            $seekers[$num] = [
                'id'    => $num,
                'uid'   => $s['id'],
                'code'  => 'SKR-' . strtoupper(substr($s['id'], 0, 8)),
                'name'  => $name ?: 'N/A',
                'email' => $s['email'] ?? 'N/A',
                'docs'  => $docNames,
            ];

            $documents = [];
            if ($hasId) {
                $documents[] = ['type' => 'Valid ID',      'status' => 'Pending', 'img' => $s['validIdUrl'],    'meta' => ['Submitted By' => $name]];
            }
            if ($hasClearance) {
                $documents[] = ['type' => 'NBI Clearance', 'status' => 'Pending', 'img' => $s['clearanceUrl'],  'meta' => ['Submitted By' => $name]];
            }

            $modal[$num] = [
                'id'        => $num,
                'uid'       => $s['id'],
                'code'      => 'SKR-' . strtoupper(substr($s['id'], 0, 8)),
                'name'      => $name ?: 'N/A',
                'email'     => $s['email'] ?? 'N/A',
                'location'  => $s['location'] ?? 'N/A',
                'status'    => 'Pending',
                'documents' => $documents,
            ];
        }

        return view('admin.seekers', ['seekers' => $seekers, 'seekersModal' => $modal]);
    }
}
