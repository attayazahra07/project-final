@extends('layouts.app')

@section('page_title', 'Manage Ports')

@section('content')
<div class="glass-card mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <h5 class="mb-0 fw-bold text-dark">Ports Management</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.ports.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Port</a>
            <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">Back to Admin</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success bg-opacity-10 border-success text-success py-2 mb-3">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger bg-danger bg-opacity-10 border-danger text-danger py-2 mb-3">
            {{ session('error') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.ports.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control bg-white text-dark border-primary" placeholder="Search ports by name or country..." value="{{ $search }}">
            <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            @if($search)
                <a href="{{ route('admin.ports.index') }}" class="btn btn-outline-danger">Clear</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-transparent mb-0">
            <thead>
                <tr class="text-dark fw-bold">
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Port Name</th>
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Country</th>
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Latitude</th>
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Longitude</th>
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Harbor Size</th>
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ports as $port)
                <tr>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25 fw-bold text-dark">{{ $port->port_name }}</td>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25 fw-bold text-dark">{{ $port->country_name }}</td>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25 fw-bold text-dark">{{ number_format($port->lat, 4) }}</td>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25 fw-bold text-dark">{{ number_format($port->lng, 4) }}</td>
                    <td class="bg-transparent border-bottom border-secondary border-opacity-25">
                        <span class="badge bg-secondary">{{ $port->harbor_size ?: 'N/A' }}</span>
                    </td>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.ports.edit', $port->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-edit"></i> Edit</a>
                            <form method="POST" action="{{ route('admin.ports.destroy', $port->id) }}" onsubmit="return confirm('Are you sure you want to delete this port?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center bg-transparent border-bottom border-primary border-opacity-25 text-dark fw-bold py-4">No ports found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($ports->hasPages())
        <div class="mt-4">
            {{ $ports->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
