<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $countries = DB::table('countries')->get();
        return view('dashboard.index', compact('countries'));
    }

    public function compare()
    {
        $countries = DB::table('countries')->get();
        return view('dashboard.compare', compact('countries'));
    }
}
