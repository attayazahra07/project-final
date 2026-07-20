@extends('layouts.app')

@section('page_title', 'Manage Users')

@section('content')
<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-bold text-dark">User Management</h5>
        <a href="{{ route('admin.index') }}" class="btn btn-sm btn-outline-secondary">Back to Admin</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success bg-opacity-10 border-success text-success py-2">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger bg-danger bg-opacity-10 border-danger text-danger py-2">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-transparent mb-0">
            <thead>
                <tr>
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Name</th>
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Email</th>
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Role</th>
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25 fw-bold text-dark">{{ $u->name }}</td>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25 fw-bold text-dark">{{ $u->email }}</td>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25">
                        <span class="badge {{ $u->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">{{ ucfirst($u->role) }}</span>
                    </td>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25">
                        <form method="POST" action="{{ route('admin.users.toggle-role', $u->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $u->role === 'admin' ? 'btn-outline-warning' : 'btn-outline-success' }}" {{ $u->id == auth()->id() ? 'disabled' : '' }}>
                                Make {{ $u->role === 'admin' ? 'User' : 'Admin' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
