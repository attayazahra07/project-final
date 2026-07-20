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

    // Ports CRUD
    public function portsIndex(Request $request)
    {
        $search = $request->query('search');
        
        $query = DB::table('ports')
            ->join('countries', 'ports.country_id', '=', 'countries.id')
            ->select('ports.*', 'countries.name as country_name');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('ports.port_name', 'like', "%{$search}%")
                  ->orWhere('countries.name', 'like', "%{$search}%");
            });
        }

        $ports = $query->paginate(15)->withQueryString();
        return view('admin.ports.index', compact('ports', 'search'));
    }

    public function portsCreate()
    {
        $countries = DB::table('countries')->orderBy('name')->get();
        return view('admin.ports.create', compact('countries'));
    }

    public function portsStore(Request $request)
    {
        $data = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'port_name' => 'required|string|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'harbor_size' => 'nullable|string|max:10',
        ]);

        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('ports')->insert($data);

        return redirect()->route('admin.ports.index')->with('success', 'Port successfully created.');
    }

    public function portsEdit($id)
    {
        $port = DB::table('ports')->where('id', $id)->first();
        if (!$port) {
            return redirect()->route('admin.ports.index')->with('error', 'Port not found.');
        }

        $countries = DB::table('countries')->orderBy('name')->get();
        return view('admin.ports.edit', compact('port', 'countries'));
    }

    public function portsUpdate(Request $request, $id)
    {
        $data = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'port_name' => 'required|string|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'harbor_size' => 'nullable|string|max:10',
        ]);

        $data['updated_at'] = now();

        DB::table('ports')->where('id', $id)->update($data);

        return redirect()->route('admin.ports.index')->with('success', 'Port successfully updated.');
    }

    public function portsDestroy($id)
    {
        DB::table('ports')->where('id', $id)->delete();
        return redirect()->route('admin.ports.index')->with('success', 'Port successfully deleted.');
    }

    // Articles CRUD
    public function articlesIndex(Request $request)
    {
        $search = $request->query('search');

        $query = DB::table('articles')
            ->leftJoin('countries', 'articles.country_id', '=', 'countries.id')
            ->join('users', 'articles.user_id', '=', 'users.id')
            ->select('articles.*', 'countries.name as country_name', 'users.name as author_name');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('articles.title', 'like', "%{$search}%")
                  ->orWhere('articles.body', 'like', "%{$search}%");
            });
        }

        $articles = $query->paginate(15)->withQueryString();
        return view('admin.articles.index', compact('articles', 'search'));
    }

    public function articlesCreate()
    {
        $countries = DB::table('countries')->orderBy('name')->get();
        return view('admin.articles.create', compact('countries'));
    }

    public function articlesStore(Request $request)
    {
        $data = $request->validate([
            'country_id' => 'nullable|exists:countries,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $data['user_id'] = auth()->id();
        $data['published_at'] = now();
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('articles')->insert($data);

        return redirect()->route('admin.articles.index')->with('success', 'Article successfully created.');
    }

    public function articlesEdit($id)
    {
        $article = DB::table('articles')->where('id', $id)->first();
        if (!$article) {
            return redirect()->route('admin.articles.index')->with('error', 'Article not found.');
        }

        $countries = DB::table('countries')->orderBy('name')->get();
        return view('admin.articles.edit', compact('article', 'countries'));
    }

    public function articlesUpdate(Request $request, $id)
    {
        $data = $request->validate([
            'country_id' => 'nullable|exists:countries,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $data['updated_at'] = now();

        DB::table('articles')->where('id', $id)->update($data);

        return redirect()->route('admin.articles.index')->with('success', 'Article successfully updated.');
    }

    public function articlesDestroy($id)
    {
        DB::table('articles')->where('id', $id)->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Article successfully deleted.');
    }
}
