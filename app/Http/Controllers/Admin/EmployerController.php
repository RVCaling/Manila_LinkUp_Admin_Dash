<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function index()
    {
        // CHANGE THIS: From 'admin.seekers' to 'admin.employer-verification'
        // (Or whatever you named the new file I gave you)
        return view('admin.employer-verification');
    }
}