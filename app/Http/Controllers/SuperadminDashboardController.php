<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperadminDashboardController extends Controller
{
    /**
     * Display the superadmin dashboard.
     */
    public function index(Request $request): View
    {
        return view('superadmin.dashboard', [
            'user' => $request->user(),
        ]);
    }
}
