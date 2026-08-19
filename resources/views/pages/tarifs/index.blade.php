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
        $actifs = $plans->where('est_actif', 1)->count();
        $inactifs = $plans->where('est_actif', 0)->count();
        $avecEssai = $plans->where('duree_jours', '>', 0)->count();
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
                                <div class="fw-bold text-primary">{{ $plan->nom }}</div>
                                <!-- <span class="text-muted small">{{ Str::limit($plan->description, 40) }}</span> -->
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ ucfirst($plan->frequence_facturation) }}</span></td>
                            <td class="fw-bold text-orion-dark">{{ number_format($plan->prix, 0, ',', ' ') }} FCFA <small class="text-muted">/{{ $plan->frequence_facturation == 'annuel' ? 'an' : 'mois' }}</small></td>
                            <td>
                                {{ $plan->max_agences == 0 ? 'Illimité' : $plan->max_agences }} Agence{{ $plan->max_agences > 1 ? 's' : '' }}
                                | Max {{ $plan->max_utilisateurs == 0 ? 'Illimité' : $plan->max_utilisateurs }} Utilisateur{{ $plan->max_utilisateurs > 1 ? 's' : '' }}
                            </td>
                            <td><span class="badge bg-info text-dark">—</span></td>
                            <td>
                                @if($plan->est_actif)
                                <span class="badge bg-success">Actif</span>
                                @else
                                <span class="badge bg-danger">Inactif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary me-1 btn-show-plan" data-id="{{ $plan->id }}" title="Voir détails">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary me-1 btn-edit-plan" data-id="{{ $plan->id }}" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-delete-plan" data-id="{{ $plan->id }}" title="Supprimer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
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
                            <label for="nom" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nom du Plan <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-tag"></i>
                                <input type="text" id="nom" name="nom" placeholder="Ex: Basic, Pro, Enterprise" value="{{ old('nom') }}" required>
                            </div>
                            @error('nom') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Fréquence de Facturation -->
                        <div class="col-md-6">
                            <label for="frequence_facturation" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Fréquence de Facturation <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-clock"></i>
                                <select id="frequence_facturation" name="frequence_facturation" required>
                                    <option value="mensuel" {{ old('frequence_facturation') == 'mensuel' ? 'selected' : '' }}>Mensuelle</option>
                                    <option value="trimestriel" {{ old('frequence_facturation') == 'trimestriel' ? 'selected' : '' }}>Trimestrielle</option>
                                    <option value="annuel" {{ old('frequence_facturation') == 'annuel' ? 'selected' : '' }}>Annuelle</option>
                                </select>
                            </div>
                            @error('frequence_facturation') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Prix -->
                        <div class="col-md-6">
                            <label for="prix" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Prix du Tarif (FCFA) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-money-bill"></i>
                                <input type="number" id="prix" name="prix" placeholder="Ex: 25000" value="{{ old('prix') }}" required>
                            </div>
                            @error('prix') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Période -->
                        <div class="col-md-6">
                            <label for="duree_jours" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Durée (Jours)</label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-calendar-day"></i>
                                <input type="number" id="duree_jours" name="duree_jours" placeholder="Ex: 14" value="{{ old('duree_jours', 0) }}">
                            </div>
                            @error('duree_jours') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Nombre d'agences -->
                        <div class="col-md-6">
                            <label for="max_agences" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nombre d'agences autorisées <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-building"></i>
                                <input type="number" id="max_agences" name="max_agences" placeholder="0 pour illimité" value="{{ old('max_agences') }}" required>
                            </div>
                            @error('max_agences') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Nombre max d'utilisateurs -->
                        <div class="col-md-6">
                            <label for="max_utilisateurs" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nombre max d'utilisateurs <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-users"></i>
                                <input type="number" id="max_utilisateurs" name="max_utilisateurs" placeholder="0 pour illimité" value="{{ old('max_utilisateurs') }}" required>
                            </div>
                            @error('max_utilisateurs') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
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
                                <input class="form-check-input" type="checkbox" id="est_actif" name="est_actif" value="1" {{ old('est_actif', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" style="font-size: 0.85rem; color: #495057;" for="est_actif">Rendre ce plan disponible immédiatement</label>
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

<!-- MODAL DETAILS DE PLAN (SHOW) -->
<div class="modal fade" id="showPlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #212529;">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-eye me-2" style="color: #fff;"></i>Détails du Plan : <span id="show-plan-title"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <!-- Nom du Plan -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nom du Plan</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-tag"></i>
                            <input type="text" id="show_nom" readonly class="bg-light">
                        </div>
                    </div>

                    <!-- Fréquence de Facturation -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Fréquence de Facturation</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-clock"></i>
                            <input type="text" id="show_frequence" readonly class="bg-light">
                        </div>
                    </div>

                    <!-- Prix -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Prix du Tarif (FCFA)</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-money-bill"></i>
                            <input type="text" id="show_prix" readonly class="bg-light">
                        </div>
                    </div>

                    <!-- Période -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Durée (Jours)</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-calendar-day"></i>
                            <input type="text" id="show_duree" readonly class="bg-light">
                        </div>
                    </div>

                    <!-- Nombre d'agences -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Agences autorisées</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-building"></i>
                            <input type="text" id="show_max_agences" readonly class="bg-light">
                        </div>
                    </div>

                    <!-- Nombre max d'utilisateurs -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Max utilisateurs</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-users"></i>
                            <input type="text" id="show_max_utilisateurs" readonly class="bg-light">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Description / Avantages inclus</label>
                        <div class="input-group-custom" style="align-items: flex-start;">
                            <i class="fa-solid fa-align-left" style="margin-top: 10px;"></i>
                            <textarea id="show_description" rows="3" readonly class="bg-light" style="resize: vertical;"></textarea>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Statut</label>
                        <div class="pt-1" id="show_statut_container"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-custom-outline" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL MODIFICATION DE PLAN (UPDATE) -->
<div class="modal fade" id="editPlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #212529;">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-pen-to-square me-2" style="color: #fff;"></i>Modifier le Plan : <span id="edit-plan-title"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPlanForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nom du Plan <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-tag"></i>
                                <input type="text" id="edit_nom" name="nom" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Fréquence de Facturation <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-clock"></i>
                                <select id="edit_frequence_facturation" name="frequence_facturation" required>
                                    <option value="mensuel">Mensuelle</option>
                                    <option value="trimestriel">Trimestrielle</option>
                                    <option value="annuel">Annuelle</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Prix du Tarif (FCFA) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-money-bill"></i>
                                <input type="number" id="edit_prix" name="prix" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Durée (Jours)</label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-calendar-day"></i>
                                <input type="number" id="edit_duree_jours" name="duree_jours">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Agences autorisées <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-building"></i>
                                <input type="number" id="edit_max_agences" name="max_agences" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Max utilisateurs <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-users"></i>
                                <input type="number" id="edit_max_utilisateurs" name="max_utilisateurs" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Description</label>
                            <div class="input-group-custom" style="align-items: flex-start;">
                                <i class="fa-solid fa-align-left" style="margin-top: 10px;"></i>
                                <textarea id="edit_description" name="description" rows="3" style="resize: vertical;"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit_est_actif" name="est_actif" value="1">
                                <label class="form-check-label fw-semibold" style="font-size: 0.85rem; color: #495057;" for="edit_est_actif">Rendre ce plan disponible immédiatement</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-custom-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL SUPPRESSION DE PLAN (DELETE) -->
<div class="modal fade" id="deletePlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="deletePlanForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4 text-center">
                    <i class="fa-solid fa-layer-group text-danger fa-3x mb-3"></i>
                    <p class="mb-1">Êtes-vous sûr de vouloir supprimer le plan <strong id="delete-plan-nom"></strong> ?</p>
                    <small class="text-muted">Cette action est irréversible.</small>
                </div>
                <div class="modal-footer bg-light justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash me-1"></i> Supprimer définitivement</button>
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

        @if($errors -> any())
        $('#createPlanModal').modal('show');
        @endif


        // 1. VOIR DÉTAILS (SHOW)
        // $(document).on('click', '.btn-show-plan', function() {
        //     let planId = $(this).data('id');
        //     let showUrl = "{{ route('tarifs.show', ':id') }}".replace(':id', planId);

        //     $.ajax({
        //         url: showUrl,
        //         type: 'GET',
        //         dataType: 'json',
        //         success: function(data) {
        //             $('#show_nom').text(data.nom);
        //             $('#show_frequence').text(data.frequence_facturation.toUpperCase());
        //             $('#show_prix').text(new Intl.NumberFormat('fr-FR').format(data.prix) + ' FCFA');
        //             $('#show_duree').text(data.duree_jours + ' jours');
        //             $('#show_max_agences').text(data.max_agences == 0 ? 'Illimité' : data.max_agences);
        //             $('#show_max_utilisateurs').text(data.max_utilisateurs == 0 ? 'Illimité' : data.max_utilisateurs);
        //             $('#show_description').text(data.description ?? 'Aucune description spécifiée.');

        //             if (data.est_actif) {
        //                 $('#show_statut_badge').html('<span class="badge bg-success">Actif</span>');
        //             } else {
        //                 $('#show_statut_badge').html('<span class="badge bg-danger">Inactif</span>');
        //             }

        //             $('#showPlanModal').modal('show');
        //         }
        //     });
        // });

        // VOIR DÉTAILS (SHOW)
        $(document).on('click', '.btn-show-plan', function() {
            let planId = $(this).data('id');
            let showUrl = "{{ route('tarifs.show', ':id') }}".replace(':id', planId);

            $.ajax({
                url: showUrl,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#show-plan-title').text(data.nom);
                    $('#show_nom').val(data.nom ?? '');

                    // Formatage fréquence
                    let freq = data.frequence_facturation ?? '';
                    if (freq === 'mensuel') freq = 'Mensuelle';
                    else if (freq === 'trimestriel') freq = 'Trimestrielle';
                    else if (freq === 'annuel') freq = 'Annuelle';
                    $('#show_frequence').val(freq);

                    // Formatage prix & durée
                    $('#show_prix').val(new Intl.NumberFormat('fr-FR').format(data.prix) + ' FCFA');
                    $('#show_duree').val((data.duree_jours ?? 0) + ' Jours');

                    // Formatage limites
                    $('#show_max_agences').val(data.max_agences == 0 ? 'Illimité' : data.max_agences);
                    $('#show_max_utilisateurs').val(data.max_utilisateurs == 0 ? 'Illimité' : data.max_utilisateurs);

                    $('#show_description').val(data.description ?? 'Aucune description spécifiée.');

                    // Affichage du statut en badge
                    if (data.est_actif) {
                        $('#show_statut_container').html('<span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Actif</span>');
                    } else {
                        $('#show_statut_container').html('<span class="badge bg-danger"><i class="fa-solid fa-circle-xmark me-1"></i>Inactif</span>');
                    }

                    $('#showPlanModal').modal('show');
                },
                error: function() {
                    alert("Impossible de charger les détails du plan.");
                }
            });
        });

        // 2. ÉDITION DE PLAN (EDIT)
        $(document).on('click', '.btn-edit-plan', function() {
            let planId = $(this).data('id');
            let showUrl = "{{ route('tarifs.show', ':id') }}".replace(':id', planId);
            let updateUrl = "{{ route('tarifs.update', ':id') }}".replace(':id', planId);

            $('#editPlanForm').attr('action', updateUrl);

            $.ajax({
                url: showUrl,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#edit-plan-title').text(data.nom);
                    $('#edit_nom').val(data.nom);
                    $('#edit_frequence_facturation').val(data.frequence_facturation);
                    $('#edit_prix').val(data.prix);
                    $('#edit_duree_jours').val(data.duree_jours);
                    $('#edit_max_agences').val(data.max_agences);
                    $('#edit_max_utilisateurs').val(data.max_utilisateurs);
                    $('#edit_description').val(data.description);
                    $('#edit_est_actif').prop('checked', data.est_actif == 1);

                    $('#editPlanModal').modal('show');
                }
            });
        });

        // 3. SUPPRESSION DE PLAN (DELETE)
        $(document).on('click', '.btn-delete-plan', function() {
            let planId = $(this).data('id');
            let showUrl = "{{ route('tarifs.show', ':id') }}".replace(':id', planId);
            let deleteUrl = "{{ route('tarifs.destroy', ':id') }}".replace(':id', planId);

            $('#deletePlanForm').attr('action', deleteUrl);

            $.ajax({
                url: showUrl,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#delete-plan-nom').text(data.nom);
                    $('#deletePlanModal').modal('show');
                }
            });
        });
    });
</script>
@endpush
