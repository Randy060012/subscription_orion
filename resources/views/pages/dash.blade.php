@extends('layouts.master')

@push('styles')
<style>
    /* --- KPI : Barre latérale colorée --- */
    .stat-card {
        border-left: 4px solid transparent !important;
        position: relative;
        overflow: hidden;
    }
    .stat-card.accent-primary  { border-left-color: var(--orion-blue) !important; }
    .stat-card.accent-success  { border-left-color: #198754 !important; }
    .stat-card.accent-warning  { border-left-color: #ffc107 !important; }
    .stat-card.accent-info     { border-left-color: #0dcaf0 !important; }
    .stat-card.accent-danger   { border-left-color: #dc3545 !important; }

    .stat-card .trend-sparkline {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 80px;
        height: 32px;
        opacity: 0.15;
    }

    /* --- ALERTES / NOTIFICATIONS --- */
    .alert-card {
        border: none;
        border-radius: 0.75rem;
        transition: transform 0.2s ease;
    }
    .alert-card:hover {
        transform: translateY(-2px);
    }

    /* --- TIMELINE ACTIVITÉ --- */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1.25rem;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .timeline-item .timeline-dot {
        position: absolute;
        left: -2rem;
        top: 0.25rem;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        z-index: 1;
    }
    .timeline-item .timeline-time {
        font-size: 0.75rem;
        color: #6c757d;
    }

    /* --- MINI-BADGE TENDANCE --- */
    .trend-up   { color: #198754; }
    .trend-down { color: #dc3545; }
    .trend-neutral { color: #6c757d; }

    /* --- COMPTEUR KPI ANIMÉ --- */
    .kpi-number {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
    }

    /* --- BANDEAU ALERTE --- */
    .alert-bar {
        background: linear-gradient(135deg, #fff3cd 0%, #fff 100%);
        border: 1px solid #ffecb5;
        border-radius: 0.75rem;
    }
    .alert-bar-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0;
    }
    .alert-bar-item:not(:last-child) {
        border-bottom: 1px solid rgba(0,0,0,0.04);
    }

    /* --- CARTE STATUT SUCCINCT --- */
    .mini-stat {
        text-align: center;
        padding: 0.75rem;
        border-radius: 0.5rem;
        background: #f8f9fa;
        transition: background 0.2s;
    }
    .mini-stat:hover {
        background: #e9ecef;
    }
    .mini-stat .value {
        font-size: 1.1rem;
        font-weight: 700;
    }
    .mini-stat .label {
        font-size: 0.7rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* --- CHART SHIMMER (effet chargement) --- */
    .chart-container {
        position: relative;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        .kpi-number { font-size: 1.5rem; }
        .stat-card { border-left-width: 3px !important; }
    }
</style>
@endpush

@section('content')

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

    <!-- ============================================================
         HEADER : Message de bienvenue dynamique
         ============================================================ -->
    @php
        $hour = now()->format('H');
        if ($hour < 12)      $greeting = 'Bonjour';
        elseif ($hour < 18)  $greeting = 'Bon après-midi';
        else                 $greeting = 'Bonsoir';
    @endphp

    <div class="divider-line-light"></div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-2">
        <div>
            <h1 class="page-title-login mb-0">{{ $greeting }}, Admin 👋</h1>
            <p class="text-muted small mb-0 mt-1">
                <i class="fa-regular fa-calendar me-1"></i>{{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                — Voici le résumé de votre activité.
            </p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-custom btn-custom-sm me-2">
                <i class="fa-solid fa-download me-1"></i> Exporter
            </button>
            <button type="button" class="btn btn-custom-outline btn-custom-outline-sm">
                <i class="fa-solid fa-plus me-1"></i> Nouvelle Agence
            </button>
        </div>
    </div>
    <div class="divider-line-light"></div>

    <!-- ============================================================
         LIGNE 1 : CARTES KPI AVEC BARRE LATÉRALE & TENDANCES
         ============================================================ -->
    <div class="row g-3 mb-4">

        <!-- Agences -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card accent-primary shadow-sm h-100 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Agences</span>
                            <div class="kpi-number text-orion-dark" data-target="{{ $totalAgences }}">0</div>
                            <span class="trend-up small fw-medium">
                                <i class="fa-solid fa-arrow-up me-1"></i>+{{ $agencesThisMonth }} ce mois
                            </span>
                        </div>
                        <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                            <i class="fa-solid fa-building"></i>
                        </div>
                    </div>
                    <!-- Mini sparkline CSS uniquement -->
                    <div class="trend-sparkline">
                        <svg viewBox="0 0 80 32" class="w-100 h-100">
                            <polyline points="0,28 16,22 32,25 48,12 64,18 80,8"
                                fill="none" stroke="var(--orion-blue)" stroke-width="2" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Souscriptions -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card accent-success shadow-sm h-100 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Souscriptions</span>
                            <div class="kpi-number text-orion-dark" data-target="{{ $totalSubscriptions }}">0</div>
                            <span class="trend-up small fw-medium">
                                <i class="fa-solid fa-arrow-up me-1"></i>+{{ $subscriptionsThisMonth }} ce mois
                            </span>
                        </div>
                        <div class="icon-shape bg-success bg-opacity-10 text-success">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>
                    </div>
                    <div class="trend-sparkline">
                        <svg viewBox="0 0 80 32" class="w-100 h-100">
                            <polyline points="0,24 16,18 32,20 48,10 64,14 80,6"
                                fill="none" stroke="#198754" stroke-width="2" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Ouverts -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card accent-warning shadow-sm h-100 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Tickets Ouverts</span>
                            <div class="kpi-number text-orion-dark" data-target="{{ $ticketsOuverts }}">0</div>
                            <span class="trend-down small fw-medium">
                                <i class="fa-solid fa-circle-exclamation me-1"></i>{{ $ticketsUrgents }} urgents
                            </span>
                        </div>
                        <div class="icon-shape bg-warning bg-opacity-10 text-warning">
                            <i class="fa-solid fa-ticket"></i>
                        </div>
                    </div>
                    <div class="trend-sparkline">
                        <svg viewBox="0 0 80 32" class="w-100 h-100">
                            <polyline points="0,8 16,12 32,6 48,16 64,10 80,14"
                                fill="none" stroke="#ffc107" stroke-width="2" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Taux de Résolution -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card accent-info shadow-sm h-100 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Taux Résolution</span>
                            <div class="kpi-number text-orion-dark" data-target="{{ $tauxResolution * 10 }}" data-decimals="1">0</div>
                            <span class="trend-up small fw-medium">
                                <i class="fa-solid fa-circle-check me-1"></i>{{ $tauxResolution }}%
                            </span>
                        </div>
                        <div class="icon-shape bg-info bg-opacity-10 text-info">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                    </div>
                    <div class="trend-sparkline">
                        <svg viewBox="0 0 80 32" class="w-100 h-100">
                            <polyline points="0,16 16,14 32,10 48,8 64,6 80,2"
                                fill="none" stroke="#0dcaf0" stroke-width="2" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ============================================================
         LIGNE 2 : BANDEAU D'ALERTES / NOTIFICATIONS
         ============================================================ -->
    <div class="alert-bar p-3 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-12 col-md-4 alert-bar-item">
                <div class="icon-shape bg-danger bg-opacity-10 text-danger" style="width:36px;height:36px;font-size:0.9rem;flex-shrink:0;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <strong class="d-block">{{ $ticketsUrgents }} tickets urgents</strong>
                    <small class="text-muted">Nécessitent une attention immédiate</small>
                </div>
            </div>
            <div class="col-12 col-md-4 alert-bar-item">
                <div class="icon-shape bg-warning bg-opacity-10 text-warning" style="width:36px;height:36px;font-size:0.9rem;flex-shrink:0;">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <strong class="d-block">{{ $agencesEssai }} agences en période d'essai</strong>
                    <small class="text-muted">Expirent dans les 7 jours</small>
                </div>
            </div>
            <div class="col-12 col-md-4 alert-bar-item">
                <div class="icon-shape bg-success bg-opacity-10 text-success" style="width:36px;height:36px;font-size:0.9rem;flex-shrink:0;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <strong class="d-block">{{ $totalSubscriptions }} souscriptions</strong>
                    <small class="text-muted">Au total</small>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
         LIGNE 3 : GRAPHIQUES AMÉLIORÉS
         ============================================================ -->
    <div class="row g-3 mb-4">

        <!-- Courbe : Évolution des souscriptions (AMÉLIORÉ) -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-orion-dark">Évolution des Souscriptions</h5>
                        <small class="text-muted">Comparaison 2025 — 2026</small>
                    </div>
                    <span class="badge badge-custom-dark px-3 py-2">Année 2026</span>
                </div>
                <div class="card-body chart-container">
                    <canvas id="subscriptionsChart" height="140"></canvas>
                </div>
            </div>
        </div>

        <!-- Donut : Statut des Tickets (AMÉLIORÉ) -->
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="card-title mb-0 fw-bold text-orion-dark">Statut des Tickets</h5>
                    <small class="text-muted">Répartition actuelle</small>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center chart-container">
                    <canvas id="ticketsChart" height="200"></canvas>
                </div>
                <!-- Mini stats sous le donut -->
                <div class="px-3 pb-3">
                    <div class="row g-2">
                        <div class="col-4 mini-stat">
                            <div class="value text-primary">{{ $ticketsNouveaux }}</div>
                            <div class="label">Nouveaux</div>
                        </div>
                        <div class="col-4 mini-stat">
                            <div class="value text-warning">{{ $ticketsEnCours }}</div>
                            <div class="label">En cours</div>
                        </div>
                        <div class="col-4 mini-stat">
                            <div class="value text-success">{{ $ticketsResolusCount }}</div>
                            <div class="label">Résolus</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ============================================================
         LIGNE 4 : ACTIVITÉ RÉCENTE + RÉPARTITION REVENUS
         ============================================================ -->
    <div class="row g-3 mb-4">

        <!-- Fil d'Activité Récente -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-orion-dark">
                        <i class="fa-regular fa-clock me-1"></i> Activité Récente
                    </h5>
                    <a href="#" class="btn btn-custom btn-custom-sm">Voir tout</a>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($recentActivities as $activity)
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $activity->icon_bg }} text-white">
                                <i class="fa-solid fa-{{ $activity->icon }} fa-xs"></i>
                            </div>
                            <div>
                                <strong>{{ $activity->title }}</strong>
                                <div class="text-muted small">{{ $activity->subtitle }}</div>
                                <div class="timeline-time"><i class="fa-regular fa-clock me-1"></i>{{ $activity->time }}</div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-3">
                            <i class="fa-regular fa-clock me-1"></i>Aucune activité récente.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Agences / Performance -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-orion-dark">
                        <i class="fa-solid fa-trophy me-1 text-warning"></i> Top Agences
                    </h5>
                    <span class="small text-muted">Par volume de tickets résolus</span>
                </div>
                <div class="card-body">
                    @php $colors = ['bg-success', 'bg-primary', 'bg-warning', 'bg-info']; @endphp
                    @foreach($topAgences as $index => $agence)
                    <div class="d-flex align-items-center {{ !$loop->last ? 'mb-3' : '' }}">
                        <div class="icon-shape bg-warning bg-opacity-10 text-warning me-3" style="width:40px;height:40px;font-size:1rem;">
                            <i class="fa-solid {{ $index == 0 ? 'fa-trophy' : ($index == 1 ? 'fa-medal' : ($index == 2 ? 'fa-award' : 'fa-medal')) }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>{{ $agence->name }}</strong>
                                <span class="fw-bold text-orion-dark">{{ $agence->score }}</span>
                            </div>
                            <div class="progress" style="height:6px;">
                                <div class="progress-bar {{ $colors[$index] ?? 'bg-info' }}" style="width:{{ $agence->maxScore > 0 ? round(($agence->score / $agence->maxScore) * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

</main>

@endsection

@push('scripts')
<script>
    // --- CONFIG GLOBALE CHART.JS ---
    Chart.defaults.font.family = "'Ubuntu', sans-serif";
    Chart.defaults.plugins.tooltip.backgroundColor = '#212529';
    Chart.defaults.plugins.tooltip.titleColor = '#fff';
    Chart.defaults.plugins.tooltip.bodyColor = '#ddd';
    Chart.defaults.plugins.tooltip.padding = 10;
    Chart.defaults.plugins.tooltip.cornerRadius = 6;

    // --- 1. GRAPHIQUE ÉVOLUTION DES SOUSCRIPTIONS ---
    const ctxSub = document.getElementById('subscriptionsChart').getContext('2d');
    new Chart(ctxSub, {
        type: 'line',
        data: {
            labels: @json($chartMonths),
            datasets: [{
                label: 'Souscriptions (2026)',
                data: [{{ implode(',', $subData2026) }}],
                borderColor: '#2E81E9',
                backgroundColor: (ctx) => {
                    const grad = ctx.chart.ctx.createLinearGradient(0, 0, 0, 300);
                    grad.addColorStop(0, 'rgba(46, 129, 233, 0.25)');
                    grad.addColorStop(1, 'rgba(46, 129, 233, 0.01)');
                    return grad;
                },
                fill: true,
                tension: 0.35,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#2E81E9',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
            }, {
                label: 'Souscriptions (2025)',
                data: [{{ implode(',', $subData2025) }}],
                borderColor: '#adb5bd',
                backgroundColor: 'rgba(173, 181, 189, 0.05)',
                fill: false,
                tension: 0.35,
                borderWidth: 2,
                borderDash: [5, 5],
                pointRadius: 0,
                pointHoverRadius: 4,
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 15,
                        padding: 15,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: (ctx) => ctx.dataset.label + ' : ' + ctx.parsed.y + ' souscriptions'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: { stepSize: 10 }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // --- 2. GRAPHIQUE STATUT DES TICKETS ---
    const ctxTicket = document.getElementById('ticketsChart').getContext('2d');
    new Chart(ctxTicket, {
        type: 'doughnut',
        data: {
            labels: ['Nouveaux ({{ $ticketsNouveaux }})', 'En cours ({{ $ticketsEnCours }})', 'Résolus ({{ $ticketsResolusCount }})'],
            datasets: [{
                data: [{{ $ticketsNouveaux }}, {{ $ticketsEnCours }}, {{ $ticketsResolusCount }}],
                backgroundColor: ['#2E81E9', '#ffc107', '#198754'],
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            cutout: '68%',
            animation: {
                animateRotate: true,
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: {
                    display: false  // On a les mini-stats en dessous
                },
                tooltip: {
                    callbacks: {
                        label: (ctx) => ctx.label + ' — ' + ctx.parsed + ' tickets'
                    }
                }
            }
        }
    });

    // --- 3. COMPTEUR ANIMÉ SUR LES KPI AU CHARGEMENT ---
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.kpi-number');

        counters.forEach(counter => {
            const rawTarget = parseFloat(counter.dataset.target);
            const decimals = parseInt(counter.dataset.decimals) || 0;
            // Pour le taux de résolution : target stocké comme 942, affiché 94.2%
            const displayTarget = decimals > 0 ? rawTarget / 10 : rawTarget;
            const startTime = performance.now();
            const animDuration = 1000; // ms

            const update = (now) => {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / animDuration, 1);
                // Ease out cubic
                const eased = 1 - Math.pow(1 - progress, 3);

                if (counter.dataset.decimals) {
                    counter.textContent = (displayTarget * eased).toFixed(1) + '%';
                } else {
                    counter.textContent = Math.round(displayTarget * eased);
                }

                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            };

            // Délai staggered aléatoire
            setTimeout(() => requestAnimationFrame(update), Math.random() * 300);
        });
    });
</script>
@endpush
