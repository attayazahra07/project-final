<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $countries = DB::table('countries')->orderBy('name')->get();
        
        $userId = auth()->id();
        $watchlistCountries = DB::table('countries')
            ->join('watchlists', 'countries.id', '=', 'watchlists.country_id')
            ->where('watchlists.user_id', $userId)
            ->select('countries.*')
            ->orderBy('countries.name')
            ->get();
            
        $watchlistIds = $watchlistCountries->pluck('id')->toArray();

        return view('dashboard.index', compact('countries', 'watchlistCountries', 'watchlistIds'));
    }

    public function compare()
    {
        $countries = DB::table('countries')->orderBy('name')->get();
        return view('dashboard.compare', compact('countries'));
    }
}
