<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * Display the authenticated user's dashboard.
     */
    public function index()
    {
        return view('dashboard.index');
    }
}