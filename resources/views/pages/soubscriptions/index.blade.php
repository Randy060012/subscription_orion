@extends('layouts.master')

@push('styles')
<!-- DataTables Bootstrap 5 CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button.active .page-link {
        background-color: #212529;
        border-color: #212529;
    }

    /* Anti-débordement pour DataTable */
    #subscriptionsTable {
        width: 100% !important;
    }

    /* Conserver les boutons d'action alignés sans saut de ligne */
    .action-btns {
        white-space: nowrap;
        display: inline-flex;
        gap: 4px;
    }
</style>
@endpush

@section('content')

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

    <!-- HEADER DE PAGE (style login) -->
    <div class="divider-line-light"></div>
    <div class="mb-3">
        <h1 class="page-title-login mb-0">Gestion des Abonnements</h1>
        <p class="text-muted small mb-0 mt-1">Suivi des forfaits, pass mensuels et souscriptions passagers.</p>
    </div>
    <div class="divider-line-light"></div>

    <!-- FLASH MESSAGES (success/error) -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 py-2" role="alert">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 py-2" role="alert">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0 small">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- CARTES STATISTIQUES (KPI) -->
    <div class="row g-3 mb-4">
        <!-- Total Abonnés Actifs -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Abonnés Actifs</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">{{ $totalActifs }}</h3>
                        <span class="text-success small fw-medium"><i class="fa-solid fa-id-card me-1"></i>Souscriptions en cours</span>
                    </div>
                    <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chiffre d'affaires Abonnements -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Revenu Forfaits</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">{{ number_format($revenuTotal, 0, ',', ' ') }} FCFA</h3>
                        <span class="text-success small fw-medium"><i class="fa-solid fa-chart-line me-1"></i>Total actifs</span>
                    </div>
                    <div class="icon-shape bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expirations imminentes -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Expire Sous 7j</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">{{ $expiringSoon }}</h3>
                        <span class="text-warning small fw-medium"><i class="fa-solid fa-bell me-1"></i>Relance requise</span>
                    </div>
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Abonnements Expirés -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Abonnements Expirés</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">{{ $expired }}</h3>
                        <span class="text-danger small fw-medium"><i class="fa-solid fa-circle-xmark me-1"></i>Terminés</span>
                    </div>
                    <div class="icon-shape bg-danger bg-opacity-10 text-danger">
                        <i class="fa-solid fa-user-slash"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DATATABLE DES ABONNEMENTS -->
    <div class="card shadow-sm border-0 bg-white">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title mb-0 fw-bold text-orion-dark">Liste des Abonnements</h5>
            <div class="btn-toolbar text-nowrap">
                <button type="button" class="btn btn-custom btn-custom-sm me-2">
                    <i class="fa-solid fa-download me-1"></i> Exporter
                </button>
                <button type="button" class="btn btn-custom-outline btn-custom-outline-sm" data-bs-toggle="modal" data-bs-target="#createSubscriptionModal">
                    <i class="fa-solid fa-plus me-1"></i> Nouvel Abonnement
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="subscriptionsTable" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap">#ID</th>
                            <th>Abonné / Client</th>
                            <th class="text-nowrap">Type de Forfait</th>
                            <th class="text-nowrap">Date Début</th>
                            <th class="text-nowrap">Date Fin</th>
                            <th class="text-nowrap">Prix</th>
                            <th class="text-nowrap">Statut</th>
                            <th class="text-end text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($soubscriptions as $soubscription)
                        <tr>
                            <td class="text-nowrap"><strong>{{ $loop->iteration }}</strong></td>
                            <td>
                                <div class="fw-bold">{{ $soubscription->agence->nom ?? '—' }}</div>
                                <span class="text-muted small">{{ $soubscription->agence->email ?? '' }}{{ $soubscription->agence->telephone ? ' | '.$soubscription->agence->telephone : '' }}</span>
                            </td>
                            <td class="text-nowrap">
                                <span class="badge bg-light text-dark border">
                                    {{ $soubscription->tarif->nom ?? '—' }}
                                </span>
                            </td>
                            <td class="text-nowrap">{{ $soubscription->date_debut ? $soubscription->date_debut->format('d/m/Y') : '—' }}</td>
                            <td class="text-nowrap">{{ $soubscription->date_fin ? $soubscription->date_fin->format('d/m/Y') : '—' }}</td>
                            <td class="fw-bold text-orion-dark text-nowrap">
                                {{ $soubscription->tarif ? number_format($soubscription->tarif->prix, 0, ',', ' ').' FCFA' : '—' }}
                            </td>
                            <td class="text-nowrap">
                                @if($soubscription->status_label === 'actif')
                                <span class="badge bg-success">Actif</span>
                                @elseif($soubscription->status_label === 'expire')
                                <span class="badge bg-danger">Expiré</span>
                                @else
                                <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <div class="action-btns">
                                    <button class="btn btn-sm btn-outline-secondary" title="Voir"><i class="fa-solid fa-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-primary" title="Modifier"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn btn-sm btn-outline-danger" title="Annuler"><i class="fa-solid fa-ban"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>

<!-- MODAL CREATION ABONNEMENT -->
<div class="modal fade" id="createSubscriptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #212529;">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-id-card me-2" style="color: #fff;"></i>Souscrire un Abonnement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('subscriptions.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">

                        <!-- Sélection de l'Agence -->
                        <div class="col-md-6">
                            <label for="agence_id" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">
                                Agence <span class="text-danger">*</span>
                            </label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-building"></i>
                                <select id="agence_id" name="agence_id" required>
                                    <option value="" disabled selected>Sélectionner une agence</option>
                                    @foreach($agences as $agence)
                                    <option value="{{ $agence->id }}" {{ old('agence_id') == $agence->id ? 'selected' : '' }}>
                                        {{ $agence->nom }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Sélection du Tarif -->
                        <div class="col-md-6">
                            <label for="tarif_id" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">
                                Forfait sélectionné <span class="text-danger">*</span>
                            </label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-tag"></i>
                                <select id="tarif_id" name="tarif_id" required>
                                    <option value="" disabled selected>Sélectionner un tarif</option>
                                    @foreach($tarifs as $tarif)
                                    <option value="{{ $tarif->id }}"
                                        data-days="{{ $tarif->duree_jours }}"
                                        {{ old('tarif_id') == $tarif->id ? 'selected' : '' }}>
                                        {{ $tarif->nom }} ({{ number_format($tarif->prix, 0, ',', ' ') }} FCFA / {{ $tarif->duree_jours }} jours)
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Date de Début (Aujourd'hui) -->
                        <div class="col-md-6">
                            <label for="date_debut" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">
                                Date de début (Aujourd'hui)
                            </label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-calendar"></i>
                                <input type="date" id="date_debut" readonly class="bg-light" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <!-- Date de Fin (Calculée) -->
                        <div class="col-md-6">
                            <label for="date_fin" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">
                                Date de fin d'abonnement
                            </label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-calendar-check"></i>
                                <input type="date" id="date_fin" readonly class="bg-light" placeholder="Calcul automatique...">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-custom-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-custom">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Valider l'abonnement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#subscriptionsTable').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json"
            },
            columnDefs: [{
                    orderable: false,
                    targets: 7
                },
                {
                    className: "text-nowrap",
                    targets: [0, 2, 3, 4, 5, 6, 7]
                }
            ]
        });

        @if($errors->any())
        $('#createSubscriptionModal').modal('show');
        @endif
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tarifSelect = document.getElementById('tarif_id');
        const dateDebutInput = document.getElementById('date_debut');
        const dateFinInput = document.getElementById('date_fin');

        function updateDates() {
            const selectedOption = tarifSelect.options[tarifSelect.selectedIndex];
            const days = parseInt(selectedOption ? selectedOption.getAttribute('data-days') : 0);

            if (days > 0) {
                const startDate = new Date(); // Date courante

                // Format Début (YYYY-MM-DD)
                const dYear = startDate.getFullYear();
                const dMonth = String(startDate.getMonth() + 1).padStart(2, '0');
                const dDay = String(startDate.getDate()).padStart(2, '0');
                dateDebutInput.value = `${dYear}-${dMonth}-${dDay}`;

                // Calcul de la Date de Fin
                const endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + days);

                const fYear = endDate.getFullYear();
                const fMonth = String(endDate.getMonth() + 1).padStart(2, '0');
                const fDay = String(endDate.getDate()).padStart(2, '0');

                dateFinInput.value = `${fYear}-${fMonth}-${fDay}`;
            }
        }

        tarifSelect.addEventListener('change', updateDates);
        updateDates(); // Exécution au chargement
    });
</script>
@endpush
