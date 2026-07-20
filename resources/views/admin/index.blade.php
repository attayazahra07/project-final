@extends('layouts.app')

@section('page_title', 'Admin Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="glass-card text-center">
            <h1 class="text-primary fw-bold">{{ $usersCount }}</h1>
            <h6 class="text-muted">Total Users</h6>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card text-center">
            <h1 class="text-success fw-bold">{{ $countriesCount }}</h1>
            <h6 class="text-muted">Countries in DB</h6>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card text-center">
            <h1 class="text-warning fw-bold">{{ $portsCount }}</h1>
            <h6 class="text-muted">Monitored Ports</h6>
        </div>
    </div>
</div>

<div class="glass-card">
    <h5 class="fw-bold mb-4">Admin Controls</h5>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('admin.users') }}" class="btn btn-outline-primary"><i class="fa-solid fa-users"></i> Manage Users</a>
        <a href="{{ route('admin.ports.index') }}" class="btn btn-outline-success"><i class="fa-solid fa-ship"></i> Manage Ports</a>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-info"><i class="fa-solid fa-newspaper"></i> Manage Articles</a>
    </div>
</div>
@endsection
