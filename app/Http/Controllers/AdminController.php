<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $usersCount = DB::table('users')->count();
        $portsCount = DB::table('ports')->count();
        $countriesCount = DB::table('countries')->count();
        
        return view('admin.index', compact('usersCount', 'portsCount', 'countriesCount'));
    }

    public function users()
    {
        $users = DB::table('users')->get();
        return view('admin.users', compact('users'));
    }
    
    // Simple toggle for admin role demonstration
    public function toggleUserRole(Request $request, $id)
    {
        if ($id == auth()->id()) {
            return back()->with('error', 'Cannot change your own role.');
        }
        
        $user = DB::table('users')->where('id', $id)->first();
        if ($user) {
            $newRole = $user->role === 'admin' ? 'user' : 'admin';
            DB::table('users')->where('id', $id)->update(['role' => $newRole]);
        }
        
        return back()->with('success', 'User role updated.');
    }
}
