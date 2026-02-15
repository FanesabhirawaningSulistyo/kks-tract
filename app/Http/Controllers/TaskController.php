<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        return view('dashboard.kelolatask');
    }

    public function index2(): View
    {
        return view('dashboard.taskkaryawan');
    }

    public function kelolaproject(): View
    {
        return view('dashboard.kelolaproject');
    }
}
