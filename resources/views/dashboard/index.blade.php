@extends('layouts.app')

@push('styles')
<style>
.port-label {
    background: rgba(15, 23, 42, 0.95) !important;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    color: #f8fafc !important;
    font-size: 0.7rem !important;
    font-weight: 600 !important;
    border-radius: 0.25rem !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3) !important;
    padding: 2px 6px !important;
}
.port-label::before {
    border-right-color: rgba(15, 23, 42, 0.95) !important;
}
</style>
@endpush

@section('page_title', 'Global Risk Overview')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="glass-card d-flex align-items-center gap-3 border-start border-primary border-4">
            <div class="bg-primary bg-opacity-10 p-3 rounded text-primary fs-3">
                <i class="fa-solid fa-earth-americas"></i>
            </div>
            <div>
                <h6 class="text-secondary mb-1">Monitored Countries</h6>
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
                <h6 class="text-secondary mb-1">Country Risk Status</h6>
                <h3 class="mb-0 fw-bold" id="countryRiskVal">-</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card d-flex align-items-center gap-3 border-start border-warning border-4">
            <div class="bg-warning bg-opacity-10 p-3 rounded text-warning fs-3">
                <i class="fa-solid fa-ship"></i>
            </div>
            <div>
                <h6 class="text-secondary mb-1">Ports in Country</h6>
                <h3 class="mb-0 fw-bold" id="portCount">-</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card d-flex align-items-center gap-3 border-start border-success border-4">
            <div class="bg-success bg-opacity-10 p-3 rounded text-success fs-3">
                <i class="fa-solid fa-cloud-bolt"></i>
            </div>
            <div>
                <h6 class="text-secondary mb-1">Local Wind & Temp</h6>
                <h3 class="mb-0 fw-bold" id="localWeatherVal">-</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold">Global Port Distribution & Risk Map</h5>
                <select id="mapCountrySelect" class="form-select bg-white text-dark border-secondary w-auto">
                    <option value="">-- Select Country --</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->code }}" data-id="{{ $c->id }}" data-lat="{{ $c->lat }}" data-lng="{{ $c->lng }}">{{ $c->name }} ({{ $c->code }})</option>
                    @endforeach
                </select>
            </div>
            <div id="map" style="height: 400px; border-radius: 0.5rem; background-color: #1e293b;"></div>
        </div>
    </div>
    <div class="col-md-4" id="riskChartCard">
        <div class="glass-card" style="background-color: #bbdefb; border-color: rgba(0,0,0,0.1);">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold text-dark" id="riskChartTitle">Risk Profile: Indonesia (ID)</h5>
                <button id="toggleWatchlistBtn" class="btn btn-sm btn-outline-dark border-0" onclick="toggleWatchlist()" style="display: none;" title="Toggle Watchlist">
                    <i class="fa-regular fa-star fa-lg" id="watchlistIcon"></i>
                </button>
            </div>
            <div style="height: 250px;">
                <canvas id="riskChart"></canvas>
            </div>
            <div class="mt-4" id="currencyWidget">
                <h6 class="fw-bold text-dark">Local Currency Exchange</h6>
                <p class="text-dark mb-0 fw-bold" id="currencyRateText">Loading...</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Watchlist Quick View -->
    <div class="col-md-8" id="watchlist">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 fw-bold text-primary">My Watchlist</h5>
                <button class="btn btn-sm btn-outline-primary">View All</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle bg-transparent mb-0">
                    <thead>
                        <tr class="text-primary opacity-75">
                            <th class="bg-transparent border-bottom border-primary">Country</th>
                            <th class="bg-transparent border-bottom border-primary">Currency</th>
                            <th class="bg-transparent border-bottom border-primary">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($watchlistCountries as $c)
                        <tr class="text-primary">
                            <td class="bg-transparent border-bottom border-primary border-opacity-25">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded bg-primary text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 20px;">
                                        {{ $c->code }}
                                    </div>
                                    <span class="fw-bold">{{ $c->name }}</span>
                                </div>
                            </td>
                            <td class="bg-transparent border-bottom border-primary border-opacity-25">{{ $c->currency_code }}</td>
                            <td class="bg-transparent border-bottom border-primary border-opacity-25">
                                <button class="btn btn-sm btn-primary" onclick="loadCountryData('{{ $c->code }}')">Analyze</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-secondary py-4">Your watchlist is empty. Select a country and click the star icon to add it here.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick News -->
    <div class="col-md-4">
        <div class="glass-card" id="newsWidget">
            <h5 class="mb-4 fw-bold">Latest Intelligence</h5>
            <div class="text-center text-light opacity-75"><i class="fa-solid fa-spinner fa-spin"></i> Loading news...</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // User's watchlist data
    let userWatchlistIds = @json($watchlistIds);
    let currentCountryId = null;

    // 1. Initialize Map
    const map = L.map('map').setView([20, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    const markersGroup = L.layerGroup().addTo(map);
    let riskChart;

    // Function to load ports and render them
    function loadPorts(countryCode = '') {
        let url = '/api/ports';
        if (countryCode) {
            url += `?country=${countryCode}`;
        }
        
        fetch(url)
            .then(res => res.json())
            .then(data => {
                markersGroup.clearLayers();
                document.getElementById('portCount').innerText = data.length;
                data.forEach(port => {
                    const marker = L.circleMarker([port.lat, port.lng], {
                        radius: 6,
                        fillColor: '#3b82f6',
                        color: '#fff',
                        weight: 1.5,
                        opacity: 1,
                        fillOpacity: 0.8
                    }).addTo(markersGroup)
                    .bindPopup(`<b>${port.port_name}</b><br>${port.country_name}`);

                    if (countryCode) {
                        marker.bindTooltip(port.port_name, {
                            permanent: true,
                            direction: 'right',
                            className: 'port-label'
                        });
                    } else {
                        marker.bindTooltip(port.port_name, {
                            permanent: false,
                            direction: 'top'
                        });
                    }
                });
            });
    }

    // Dropdown change listener
    document.getElementById('mapCountrySelect').addEventListener('change', function() {
        const code = this.value;
        if (!code) {
            map.setView([20, 0], 2);
            loadPorts();
            return;
        }

        const selectedOption = this.options[this.selectedIndex];
        const lat = parseFloat(selectedOption.getAttribute('data-lat'));
        const lng = parseFloat(selectedOption.getAttribute('data-lng'));

        if (!isNaN(lat) && !isNaN(lng)) {
            map.flyTo([lat, lng], 5);
        }

        loadCountryData(code);
    });

    // Default Load Indonesia Data
    loadCountryData('ID');

    function loadCountryData(countryCode) {
        // Sync Selector Dropdown
        const selector = document.getElementById('mapCountrySelect');
        if (selector.value !== countryCode) {
            selector.value = countryCode;
        }

        // Fetch ports for this country
        loadPorts(countryCode);

        // Update Toggle Button Visibility
        const selectedOption = selector.options[selector.selectedIndex];
        currentCountryId = selectedOption ? parseInt(selectedOption.getAttribute('data-id')) : null;
        
        const toggleBtn = document.getElementById('toggleWatchlistBtn');
        const watchIcon = document.getElementById('watchlistIcon');
        
        if (currentCountryId) {
            toggleBtn.style.display = 'inline-block';
            if (userWatchlistIds.includes(currentCountryId)) {
                watchIcon.className = 'fa-solid fa-star text-warning fa-lg';
            } else {
                watchIcon.className = 'fa-regular fa-star fa-lg';
            }
        } else {
            toggleBtn.style.display = 'none';
        }

        // Fetch Risk
        fetch(`/api/risk?country=${countryCode}`)
            .then(res => res.json())
            .then(data => {
                if(data.error) return;
                
                // Update Risk Chart Title
                const countryText = selector.options[selector.selectedIndex]?.text || countryCode;
                document.getElementById('riskChartTitle').innerText = `Risk Profile: ${countryText}`;

                // Update Top Cards
                document.getElementById('countryRiskVal').innerText = `${data.total_score}% (${data.risk_label})`;
                document.getElementById('portCount').innerText = data.ports_count;
                
                // Update Weather Card
                if (data.raw_data && data.raw_data.weather) {
                    const wind = data.raw_data.weather.windspeed;
                    const temp = data.raw_data.weather.temp;
                    document.getElementById('localWeatherVal').innerText = `${wind} km/h (${temp}°C)`;
                } else {
                    document.getElementById('localWeatherVal').innerText = 'N/A';
                }

                // Update Currency Widget
                if (data.raw_data && data.raw_data.currency && data.raw_data.currency.rate) {
                    const code = data.raw_data.currency.code;
                    const rate = parseFloat(data.raw_data.currency.rate).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    document.getElementById('currencyRateText').innerHTML = `
                        <span class="fs-5 fw-bold text-success">1 USD = ${rate} ${code}</span>
                        <br><span class="text-dark small fw-bold">Risk weight contribution: ${data.breakdown.currency}%</span>
                    `;
                } else {
                    document.getElementById('currencyRateText').innerText = 'No currency data available';
                }

                // Update Chart
                const ctx = document.getElementById('riskChart').getContext('2d');
                if(riskChart) riskChart.destroy();
                
                riskChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: ['Weather', 'Inflation', 'News Sentiment', 'Currency Volatility'],
                        datasets: [{
                            label: `Risk Score (${data.risk_label})`,
                            data: [
                                data.breakdown.weather, 
                                data.breakdown.inflation, 
                                data.breakdown.news, 
                                data.breakdown.currency
                            ],
                            backgroundColor: 'rgba(239, 68, 68, 0.2)',
                            borderColor: '#ef4444',
                            pointBackgroundColor: '#ef4444',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: '#ef4444'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                angleLines: { color: 'rgba(0, 0, 0, 0.1)' },
                                grid: { color: 'rgba(0, 0, 0, 0.1)' },
                                pointLabels: { color: '#000000', font: { size: 12, weight: 'bold' } },
                                ticks: { display: false, min: 0, max: 100 }
                            }
                        },
                        plugins: { legend: { labels: { color: '#000000', font: { weight: 'bold' } } } }
                    }
                });
            });

        // Fetch News
        fetch(`/api/news?country=${countryCode}`)
            .then(res => res.json())
            .then(data => {
                const widget = document.getElementById('newsWidget');
                widget.innerHTML = `<h5 class="mb-4 fw-bold">Intelligence (${countryCode})</h5>`;
                
                if(data.news.length === 0) {
                    widget.innerHTML += '<p class="text-secondary small">No recent news found.</p>';
                    return;
                }
                
                data.news.forEach(article => {
                    const titleStr = article.title || 'Untitled';
                    const title = titleStr.length > 50 ? titleStr.substring(0, 50) + '...' : titleStr;
                    
                    const descStr = article.description || '';
                    const desc = descStr.length > 80 ? descStr.substring(0, 80) + '...' : descStr;
                    
                    const date = article.publishedAt ? new Date(article.publishedAt).toLocaleDateString() : 'Recent';
                    const sourceName = (article.source && article.source.name) ? article.source.name : 'System';
                    const url = article.url ? article.url : '#';
                    
                    widget.innerHTML += `
                        <div class="mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                            <h6 class="mb-1 fw-bold fs-6"><a href="${url}" target="_blank" class="text-dark text-decoration-none hover-primary">${title}</a></h6>
                            <p class="text-secondary mb-1 small" style="font-size: 0.75rem;">${desc}</p>
                            <div class="d-flex justify-content-between text-secondary" style="font-size: 0.7rem;">
                                <span><i class="fa-solid fa-building"></i> ${sourceName}</span>
                                <span><i class="fa-regular fa-calendar"></i> ${date}</span>
                            </div>
                        </div>
                    `;
                });
                
                // Show overall sentiment
                const senti = data.sentiment;
                let sentiColor = senti.positive > senti.negative ? 'text-success' : 'text-danger';
                widget.innerHTML += `
                    <div class="mt-3 text-center small">
                        Analysis: <span class="${sentiColor} fw-bold">Pos ${senti.positive}% | Neg ${senti.negative}%</span>
                    </div>
                `;
            });
    }


    function toggleWatchlist() {
        if (!currentCountryId) return;

        fetch('/watchlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ country_id: currentCountryId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'added') {
                userWatchlistIds.push(currentCountryId);
            } else if (data.status === 'removed') {
                userWatchlistIds = userWatchlistIds.filter(id => id !== currentCountryId);
            }
            
            // Reload page dynamically to update the watchlist table
            location.reload();
        })
        .catch(err => {
            console.error('Error toggling watchlist:', err);
            alert('Failed to update watchlist.');
        });
    }

</script>
@endpush
