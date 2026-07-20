@extends('layouts.app')

@section('page_title', 'Edit Article')

@section('content')
<div class="glass-card max-width-800 mx-auto">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-bold">Edit Article</h5>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-sm btn-outline-secondary">Back to List</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger bg-danger bg-opacity-10 border-danger text-danger py-2 mb-3">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.articles.update', $article->id) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="title" class="form-label text-muted small fw-bold">Article Title</label>
            <input type="text" class="form-control bg-dark text-white border-secondary" id="title" name="title" value="{{ old('title') ?: $article->title }}" required>
        </div>

        <div class="mb-3">
            <label for="country_id" class="form-label text-muted small fw-bold">Country Tag (optional)</label>
            <select class="form-select bg-dark text-white border-secondary" id="country_id" name="country_id">
                <option value="">-- Global (No specific country tag) --</option>
                @foreach($countries as $c)
                    <option value="{{ $c->id }}" {{ (old('country_id') ?: $article->country_id) == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->code }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="body" class="form-label text-muted small fw-bold">Article Body / Content</label>
            <textarea class="form-control bg-dark text-white border-secondary" id="body" name="body" rows="10" required style="resize: vertical;">{{ old('body') ?: $article->body }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold"><i class="fa-solid fa-save"></i> Save Changes</button>
    </form>
</div>
@endsection
