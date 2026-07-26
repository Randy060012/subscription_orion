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
</style>
@endpush

@section('content')

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

    <!-- HEADER DE PAGE (style login) -->
    <div class="divider-line-light"></div>
    <div class="mb-3">
        <h1 class="page-title-login mb-0">Plans d'Abonnement & Tarifs</h1>
        <p class="text-muted small mb-0 mt-1">Définissez et gérez les forfaits d'abonnement pour les agences et utilisateurs.</p>
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

        @php
        $totalPlans = $plans->count();
        $actifs = $plans->where('is_active', 1)->count();
        $inactifs = $plans->where('is_active', 0)->count();
        $avecEssai = $plans->where('trial_period_days', '>', 0)->count();
        @endphp

        <!-- Total Plans -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Total Plans</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">{{ $totalPlans }}</h3>
                        <span class="text-success small fw-medium"><i class="fa-solid fa-layer-group me-1"></i>Enregistrés</span>
                    </div>
                    <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plans Actifs -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Plans Actifs</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">{{ $actifs }}</h3>
                        <span class="text-success small fw-medium"><i class="fa-solid fa-circle-check me-1"></i>Disponibles</span>
                    </div>
                    <div class="icon-shape bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plans Inactifs -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Plans Inactifs</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">{{ $inactifs }}</h3>
                        <span class="text-warning small fw-medium"><i class="fa-solid fa-pause me-1"></i>Masqués</span>
                    </div>
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-pause-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avec période d'essai -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Avec Essai</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">{{ $avecEssai }}</h3>
                        <span class="text-info small fw-medium"><i class="fa-solid fa-clock me-1"></i>Période d'essai</span>
                    </div>
                    <div class="icon-shape bg-info bg-opacity-10 text-info">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DATATABLE DES PLANS -->
    <div class="card shadow-sm border-0 bg-white">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold text-orion-dark">Grille des Plans d'Abonnement</h5>
            <div class="btn-toolbar">
                <button type="button" class="btn btn-custom btn-custom-sm me-2">
                    <i class="fa-solid fa-download me-1"></i> Exporter
                </button>
                <button type="button" class="btn btn-custom-outline btn-custom-outline-sm" data-bs-toggle="modal" data-bs-target="#createPlanModal">
                    <i class="fa-solid fa-plus me-1"></i> Créer un Plan
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="plansTable" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th>#ID</th>
                            <th>Nom du Plan</th>
                            <th>Facturation</th>
                            <th>Prix (FCFA)</th>
                            <th>Limites (Agences / Utilisateurs)</th>
                            <th>Abonnés</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plans as $plan)
                        <tr>
                            <td><strong>{{ $loop->iteration }}</strong></td>
                            <td>
                                <div class="fw-bold text-primary">{{ $plan->name }}</div>
                                <span class="text-muted small">{{ Str::limit($plan->description, 40) }}</span>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ ucfirst($plan->billing_frequency) }}</span></td>
                            <td class="fw-bold text-orion-dark">{{ number_format($plan->price, 0, ',', ' ') }} FCFA <small class="text-muted">/{{ $plan->billing_frequency == 'annuel' ? 'an' : 'mois' }}</small></td>
                            <td>
                                {{ $plan->max_agencies == 0 ? 'Illimité' : $plan->max_agencies }} Agence{{ $plan->max_agencies > 1 ? 's' : '' }}
                                | Max {{ $plan->max_users == 0 ? 'Illimité' : $plan->max_users }} Utilisateur{{ $plan->max_users > 1 ? 's' : '' }}
                            </td>
                            <td><span class="badge bg-info text-dark">—</span></td>
                            <td>
                                @if($plan->is_active)
                                <span class="badge bg-success">Actif</span>
                                @else
                                <span class="badge bg-danger">Inactif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary me-1" title="Voir détails"><i class="fa-solid fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-primary me-1" title="Modifier"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-sm btn-outline-danger" title="Désactiver / Supprimer"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>

<!-- MODAL CREATION DE PLAN D'ABONNEMENT -->
<div class="modal fade" id="createPlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #212529;">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-layer-group me-2" style="color: #fff;"></i>Créer un Plan d'Abonnement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tarifs.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Nom du Plan -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nom du Plan <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-tag"></i>
                                <input type="text" id="name" name="name" placeholder="Ex: Basic, Pro, Enterprise" value="{{ old('name') }}" required>
                            </div>
                            @error('name') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Fréquence de Facturation -->
                        <div class="col-md-6">
                            <label for="billing_frequency" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Fréquence de Facturation <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-clock"></i>
                                <select id="billing_frequency" name="billing_frequency" required>
                                    <option value="mensuel" {{ old('billing_frequency') == 'mensuel' ? 'selected' : '' }}>Mensuelle</option>
                                    <option value="trimestriel" {{ old('billing_frequency') == 'trimestriel' ? 'selected' : '' }}>Trimestrielle</option>
                                    <option value="annuel" {{ old('billing_frequency') == 'annuel' ? 'selected' : '' }}>Annuelle</option>
                                </select>
                            </div>
                            @error('billing_frequency') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Prix -->
                        <div class="col-md-6">
                            <label for="price" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Prix du Tarif (FCFA) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-money-bill"></i>
                                <input type="number" id="price" name="price" placeholder="Ex: 25000" value="{{ old('price') }}" required>
                            </div>
                            @error('price') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Période d'essai -->
                        <div class="col-md-6">
                            <label for="trial_period_days" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Duree (Jours)</label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-calendar-day"></i>
                                <input type="number" id="trial_period_days" name="trial_period_days" placeholder="Ex: 14" value="{{ old('trial_period_days', 0) }}">
                            </div>
                            @error('trial_period_days') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Nombre d'agences -->
                        <div class="col-md-6">
                            <label for="max_agencies" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nombre d'agences autorisées <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-building"></i>
                                <input type="number" id="max_agencies" name="max_agencies" placeholder="0 pour illimité" value="{{ old('max_agencies') }}" required>
                            </div>
                            @error('max_agencies') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Nombre max d'utilisateurs -->
                        <div class="col-md-6">
                            <label for="max_users" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nombre max d'utilisateurs <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-users"></i>
                                <input type="number" id="max_users" name="max_users" placeholder="0 pour illimité" value="{{ old('max_users') }}" required>
                            </div>
                            @error('max_users') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Description / Avantages inclus</label>
                            <div class="input-group-custom" style="align-items: flex-start;">
                                <i class="fa-solid fa-align-left" style="margin-top: 10px;"></i>
                                <textarea id="description" name="description" rows="3" placeholder="Description des fonctionnalités incluses..." style="resize: vertical;">{{ old('description') }}</textarea>
                            </div>
                            @error('description') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Switch Actif -->
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" style="font-size: 0.85rem; color: #495057;" for="is_active">Rendre ce plan disponible immédiatement</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-custom-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-custom"><i class="fa-solid fa-floppy-disk me-1"></i> Enregistrer le Plan</button>
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
        $('#plansTable').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json"
            },
            columnDefs: [{
                orderable: false,
                targets: -1
            }]
        });

        @if($errors->any())
        $('#createPlanModal').modal('show');
        @endif
    });
</script>
@endpush
