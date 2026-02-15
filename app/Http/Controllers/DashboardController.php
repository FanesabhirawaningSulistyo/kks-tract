<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        return view('dashboard.index');
    }
    public function index3()
    {
        // Dashboard untuk pegawai
        return view('dashboard.index3');
    }
    public function index4()
    {
        // Dashboard untuk pegawai
        return view('dashboard.index4');
    }
}
