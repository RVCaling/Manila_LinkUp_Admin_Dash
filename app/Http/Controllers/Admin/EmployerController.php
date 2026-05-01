<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiService;

class EmployerController extends Controller
{
    public function index()
    {
        $raw       = app(ApiService::class)->get('/admin/employers', ['verified' => 'false'])['data'] ?? [];
        $employers = [];

        foreach (array_values($raw) as $i => $e) {
            $num          = $i + 1;
            $hasId        = !empty($e['validIdUrl']);
            $hasClearance = !empty($e['clearanceUrl']);
            $docNames     = implode(', ', array_filter([
                $hasId        ? 'Valid ID'       : null,
                $hasClearance ? 'Business Permit' : null,
            ])) ?: 'None uploaded';

            $employers[$num] = [
                'id'        => $num,
                'uid'       => $e['id'],
                'code'      => 'EMP-' . strtoupper(substr($e['id'], 0, 8)),
                'name'      => $e['fullName'] ?? 'N/A',
                'docs'      => $docNames,
                'contact'   => $e['contactPerson'] ?? ($e['fullName'] ?? 'N/A'),
                'industry'  => $e['industry'] ?? 'N/A',
                'location'  => $e['location'] ?? 'N/A',
                'validIdUrl'    => $e['validIdUrl'] ?? null,
                'clearanceUrl'  => $e['clearanceUrl'] ?? null,
            ];
        }

        return view('admin.employer-verification', compact('employers'));
    }
}
