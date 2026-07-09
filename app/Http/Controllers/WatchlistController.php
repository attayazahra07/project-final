<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WatchlistController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'country_id' => 'required|integer|exists:countries,id'
        ]);

        $userId = auth()->id();
        $countryId = $request->country_id;

        $exists = DB::table('watchlists')
            ->where('user_id', $userId)
            ->where('country_id', $countryId)
            ->exists();

        if ($exists) {
            DB::table('watchlists')
                ->where('user_id', $userId)
                ->where('country_id', $countryId)
                ->delete();
            $status = 'removed';
        } else {
            DB::table('watchlists')->insert([
                'user_id' => $userId,
                'country_id' => $countryId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $status = 'added';
        }

        return response()->json(['status' => $status, 'country_id' => $countryId]);
    }
}
