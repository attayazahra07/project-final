@extends('layouts.app')

@section('page_title', 'Manage Articles')

@section('content')
<div class="glass-card mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <h5 class="mb-0 fw-bold text-dark">Articles Management</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Article</a>
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

    <form method="GET" action="{{ route('admin.articles.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control bg-white text-dark border-primary" placeholder="Search articles by title or content..." value="{{ $search }}">
            <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            @if($search)
                <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-danger">Clear</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-transparent mb-0">
            <thead>
                <tr class="text-dark fw-bold">
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Title</th>
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Author</th>
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Country Tag</th>
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Published At</th>
                    <th class="bg-transparent border-bottom border-primary fw-bold text-dark">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $art)
                <tr>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25 fw-bold text-dark">{{ $art->title }}</td>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25 fw-bold text-dark">{{ $art->author_name }}</td>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25">
                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">{{ $art->country_name ?: 'Global' }}</span>
                    </td>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25 fw-bold text-dark small">{{ $art->published_at ? date('d M Y H:i', strtotime($art->published_at)) : 'Draft' }}</td>
                    <td class="bg-transparent border-bottom border-primary border-opacity-25">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.articles.edit', $art->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-edit"></i> Edit</a>
                            <form method="POST" action="{{ route('admin.articles.destroy', $art->id) }}" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center bg-transparent border-bottom border-primary border-opacity-25 text-dark fw-bold py-4">No articles found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($articles->hasPages())
        <div class="mt-4">
            {{ $articles->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
