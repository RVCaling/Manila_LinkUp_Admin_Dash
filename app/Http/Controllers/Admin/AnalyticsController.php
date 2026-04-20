<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        // When you connect Firebase, you will fetch the counts here 
        // and pass them to the view using compact()
        return view('admin.analytics');
    }
}