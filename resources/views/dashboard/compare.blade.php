@extends('layouts.app')

@section('page_title', 'Compare Countries')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="glass-card">
            <h5 class="fw-bold mb-4 text-dark">Country Risk Comparison</h5>
            <p class="text-dark fw-bold small">Compare the risk profiles, logistics parameters, and economic conditions of two countries side-by-side to make informed global supply chain decisions.</p>
            
            <div class="row g-3">
                <div class="col-md-5">
                    <label for="countryA" class="form-label text-dark fw-bold small">First Country</label>
                    <select id="countryA" class="form-select bg-white text-dark border-primary">
                        <option value="">-- Select First Country --</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->code }}">{{ $c->name }} ({{ $c->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end justify-content-center">
                    <button id="compareBtn" class="btn btn-primary w-100 py-2 fw-bold"><i class="fa-solid fa-scale-balanced"></i> Compare</button>
                </div>
                <div class="col-md-5">
                    <label for="countryB" class="form-label text-dark fw-bold small">Second Country</label>
                    <select id="countryB" class="form-select bg-white text-dark border-primary">
                        <option value="">-- Select Second Country --</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->code }}">{{ $c->name }} ({{ $c->code }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Comparison Results Section -->
<div class="row g-4 d-none" id="resultsSection">
    <!-- Chart comparison -->
    <div class="col-md-8">
        <div class="glass-card">
            <h5 class="fw-bold mb-4 text-dark">Risk Breakdown Comparison</h5>
            <div style="height: 350px;">
                <canvas id="comparisonChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Side-by-side Summary -->
    <div class="col-md-4">
        <div class="glass-card">
            <h5 class="fw-bold mb-4 text-dark">Risk Summary</h5>
            
            <!-- Country A -->
            <div class="mb-4 p-3 rounded bg-secondary bg-opacity-10 border border-secondary border-opacity-25">
                <h6 class="fw-bold text-primary mb-2" id="nameA">Country A</h6>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-dark fw-bold small">Total Risk Score</span>
                    <span class="fs-5 fw-bold text-dark" id="scoreA">0.00%</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-dark fw-bold small">Risk Classification</span>
                    <span class="badge bg-secondary" id="labelA">Low</span>
                </div>
            </div>

            <!-- Country B -->
            <div class="p-3 rounded bg-secondary bg-opacity-10 border border-secondary border-opacity-25">
                <h6 class="fw-bold text-success mb-2" id="nameB">Country B</h6>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-dark fw-bold small">Total Risk Score</span>
                    <span class="fs-5 fw-bold text-dark" id="scoreB">0.00%</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-dark fw-bold small">Risk Classification</span>
                    <span class="badge bg-secondary" id="labelB">Low</span>
                </div>
            </div>

            <div class="mt-4 text-center">
                <p class="small text-dark fw-bold" id="decisionVerdict">Comparing...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let compChart;

    document.getElementById('compareBtn').addEventListener('click', function() {
        const codeA = document.getElementById('countryA').value;
        const codeB = document.getElementById('countryB').value;

        if (!codeA || !codeB) {
            alert('Please select both countries for comparison.');
            return;
        }

        if (codeA === codeB) {
            alert('Please select two different countries.');
            return;
        }

        const btn = document.getElementById('compareBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Comparing...';

        // Show results section
        document.getElementById('resultsSection').classList.remove('d-none');

        // Fetch data for Country A and B
        Promise.all([
            fetch(`/api/risk?country=${codeA}`).then(res => res.json()),
            fetch(`/api/risk?country=${codeB}`).then(res => res.json())
        ])
        .then(([dataA, dataB]) => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-scale-balanced"></i> Compare';
            const nameA = document.getElementById('countryA').options[document.getElementById('countryA').selectedIndex].text;
            const nameB = document.getElementById('countryB').options[document.getElementById('countryB').selectedIndex].text;

            // Update Summaries
            document.getElementById('nameA').innerText = nameA;
            document.getElementById('scoreA').innerText = `${dataA.total_score}%`;
            document.getElementById('labelA').innerText = dataA.risk_label;
            document.getElementById('labelA').className = `badge bg-${getBadgeColor(dataA.risk_label)}`;

            document.getElementById('nameB').innerText = nameB;
            document.getElementById('scoreB').innerText = `${dataB.total_score}%`;
            document.getElementById('labelB').innerText = dataB.risk_label;
            document.getElementById('labelB').className = `badge bg-${getBadgeColor(dataB.risk_label)}`;

            // Decision Verdict
            let verdict = '';
            if (dataA.total_score < dataB.total_score) {
                verdict = `<strong>Verdict:</strong> ${nameA} is a safer import route with a risk score of ${dataA.total_score}% compared to ${nameB}'s ${dataB.total_score}%.`;
            } else if (dataA.total_score > dataB.total_score) {
                verdict = `<strong>Verdict:</strong> ${nameB} is a safer import route with a risk score of ${dataB.total_score}% compared to ${nameA}'s ${dataA.total_score}%.`;
            } else {
                verdict = `<strong>Verdict:</strong> Both ${nameA} and ${nameB} present an equivalent risk profile of ${dataA.total_score}%.`;
            }
            document.getElementById('decisionVerdict').innerHTML = verdict;

            // Render/Update Chart.js Comparison
            const ctx = document.getElementById('comparisonChart').getContext('2d');
            if (compChart) compChart.destroy();

            compChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Weather Risk', 'Inflation Risk', 'News Sentiment Risk', 'Currency Risk'],
                    datasets: [
                        {
                            label: nameA,
                            data: [
                                dataA.breakdown.weather,
                                dataA.breakdown.inflation,
                                dataA.breakdown.news,
                                dataA.breakdown.currency
                            ],
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: '#3b82f6',
                            borderWidth: 1
                        },
                        {
                            label: nameB,
                            data: [
                                dataB.breakdown.weather,
                                dataB.breakdown.inflation,
                                dataB.breakdown.news,
                                dataB.breakdown.currency
                            ],
                            backgroundColor: 'rgba(16, 185, 129, 0.7)',
                            borderColor: '#10b981',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#94a3b8' }
                        },
                        x: {
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#94a3b8' }
                        }
                    },
                    plugins: {
                        legend: { labels: { color: '#f8fafc' } }
                    }
                }
            });
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-scale-balanced"></i> Compare';
            console.error(err);
            alert('Failed to load comparison data. Please try again.');
        });
    });

    function getBadgeColor(label) {
        switch(label.toLowerCase()) {
            case 'low': return 'success';
            case 'medium': return 'warning';
            case 'high': return 'danger';
            default: return 'secondary';
        }
    }
</script>
@endpush
