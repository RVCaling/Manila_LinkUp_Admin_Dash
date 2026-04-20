<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // This looks for the file in resources/views/admin/seekers.blade.php
        return view('admin.users');
    }
}   