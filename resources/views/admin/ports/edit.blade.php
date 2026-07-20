@extends('layouts.app')

@section('page_title', 'Edit Port')

@section('content')
<div class="glass-card max-width-600 mx-auto">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-bold">Edit Port</h5>
        <a href="{{ route('admin.ports.index') }}" class="btn btn-sm btn-outline-secondary">Back to List</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger bg-danger bg-opacity-10 border-danger text-danger py-2 mb-3">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.ports.update', $port->id) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="country_id" class="form-label text-muted small fw-bold">Country</label>
            <select class="form-select bg-dark text-white border-secondary" id="country_id" name="country_id" required>
                <option value="">-- Select Country --</option>
                @foreach($countries as $c)
                    <option value="{{ $c->id }}" {{ (old('country_id') ?: $port->country_id) == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->code }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="port_name" class="form-label text-muted small fw-bold">Port Name</label>
            <input type="text" class="form-control bg-dark text-white border-secondary" id="port_name" name="port_name" value="{{ old('port_name') ?: $port->port_name }}" required>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="lat" class="form-label text-muted small fw-bold">Latitude</label>
                <input type="number" step="0.000001" min="-90" max="90" class="form-control bg-dark text-white border-secondary" id="lat" name="lat" value="{{ old('lat') ?: $port->lat }}" required>
            </div>
            <div class="col-md-6">
                <label for="lng" class="form-label text-muted small fw-bold">Longitude</label>
                <input type="number" step="0.000001" min="-180" max="180" class="form-control bg-dark text-white border-secondary" id="lng" name="lng" value="{{ old('lng') ?: $port->lng }}" required>
            </div>
        </div>

        <div class="mb-4">
            <label for="harbor_size" class="form-label text-muted small fw-bold">Harbor Size (optional)</label>
            <select class="form-select bg-dark text-white border-secondary" id="harbor_size" name="harbor_size">
                <option value="">-- Select Size --</option>
                <option value="V" {{ (old('harbor_size') ?: $port->harbor_size) == 'V' ? 'selected' : '' }}>Very Small (V)</option>
                <option value="S" {{ (old('harbor_size') ?: $port->harbor_size) == 'S' ? 'selected' : '' }}>Small (S)</option>
                <option value="M" {{ (old('harbor_size') ?: $port->harbor_size) == 'M' ? 'selected' : '' }}>Medium (M)</option>
                <option value="L" {{ (old('harbor_size') ?: $port->harbor_size) == 'L' ? 'selected' : '' }}>Large (L)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold"><i class="fa-solid fa-save"></i> Save Changes</button>
    </form>
</div>
@endsection
