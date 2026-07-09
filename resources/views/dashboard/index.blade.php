@extends('layouts.app')

@section('page_title', 'Global Risk Overview')

@section('content')
<div class="row g-4 mb-4">
    <!-- Summary Cards -->
    <div class="col-md-3">
        <div class="glass-card d-flex align-items-center gap-3 border-start border-primary border-4">
            <div class="bg-primary bg-opacity-10 p-3 rounded text-primary fs-3">
                <i class="fa-solid fa-earth-americas"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1">Monitored Countries</h6>
                <h3 class="mb-0 fw-bold">{{ count($countries) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card d-flex align-items-center gap-3 border-start border-danger border-4">
            <div class="bg-danger bg-opacity-10 p-3 rounded text-danger fs-3">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1">High Risk Alerts</h6>
                <h3 class="mb-0 fw-bold">3</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card d-flex align-items-center gap-3 border-start border-warning border-4">
            <div class="bg-warning bg-opacity-10 p-3 rounded text-warning fs-3">
                <i class="fa-solid fa-ship"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1">Logistics Issues</h6>
                <h3 class="mb-0 fw-bold">12</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card d-flex align-items-center gap-3 border-start border-success border-4">
            <div class="bg-success bg-opacity-10 p-3 rounded text-success fs-3">
                <i class="fa-solid fa-check-double"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1">Stable Regions</h6>
                <h3 class="mb-0 fw-bold">5</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Watchlist Quick View -->
    <div class="col-md-8">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 fw-bold">My Watchlist</h5>
                <button class="btn btn-sm btn-outline-primary">View All</button>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle bg-transparent mb-0">
                    <thead>
                        <tr class="text-muted">
                            <th class="bg-transparent border-bottom border-secondary">Country</th>
                            <th class="bg-transparent border-bottom border-secondary">Total Risk</th>
                            <th class="bg-transparent border-bottom border-secondary">Currency</th>
                            <th class="bg-transparent border-bottom border-secondary">Weather</th>
                            <th class="bg-transparent border-bottom border-secondary">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($countries->take(5) as $c)
                        <tr>
                            <td class="bg-transparent border-bottom border-secondary border-opacity-25">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded bg-secondary d-flex align-items-center justify-content-center" style="width: 30px; height: 20px;">
                                        {{ $c->code }}
                                    </div>
                                    <span class="fw-bold">{{ $c->name }}</span>
                                </div>
                            </td>
                            <td class="bg-transparent border-bottom border-secondary border-opacity-25">
                                <span class="badge bg-success bg-opacity-25 text-success border border-success">Low (24%)</span>
                            </td>
                            <td class="bg-transparent border-bottom border-secondary border-opacity-25">{{ $c->currency_code }}</td>
                            <td class="bg-transparent border-bottom border-secondary border-opacity-25">Clear</td>
                            <td class="bg-transparent border-bottom border-secondary border-opacity-25">
                                <button class="btn btn-sm btn-primary">Details</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick News -->
    <div class="col-md-4">
        <div class="glass-card">
            <h5 class="mb-4 fw-bold">Latest Intelligence</h5>
            
            <div class="d-flex gap-3 mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                <div class="text-danger mt-1"><i class="fa-solid fa-circle-exclamation"></i></div>
                <div>
                    <h6 class="mb-1 fw-bold fs-6">Port Strike in Germany</h6>
                    <p class="text-muted mb-0 small">Major logistics delay expected in Hamburg port due to labor strike.</p>
                </div>
            </div>
            
            <div class="d-flex gap-3 mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                <div class="text-warning mt-1"><i class="fa-solid fa-cloud-bolt"></i></div>
                <div>
                    <h6 class="mb-1 fw-bold fs-6">Typhoon Warning JP</h6>
                    <p class="text-muted mb-0 small">Approaching typhoon may disrupt East Asia shipping routes.</p>
                </div>
            </div>
            
            <div class="d-flex gap-3">
                <div class="text-success mt-1"><i class="fa-solid fa-arrow-trend-up"></i></div>
                <div>
                    <h6 class="mb-1 fw-bold fs-6">US Inflation Drops</h6>
                    <p class="text-muted mb-0 small">Economic stability improves, lowering financial risk score.</p>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
