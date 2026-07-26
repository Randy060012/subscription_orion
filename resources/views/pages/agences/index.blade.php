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

    <!-- HEADER DE PAGE -->
    <div class="divider-line-light"></div>
    <div class="mb-3">
        <h1 class="page-title-login mb-0">Gestion des Agences</h1>
        <p class="text-muted small mb-0 mt-1">Liste, suivi et création de vos agences partenaires.</p>
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
        $totalAgences = $agences->count();
        $actives = $agences->where('statut', 1)->count();
        $inactives = $agences->where('statut', 0)->count();
        $villes = $agences->pluck('ville')->filter()->unique()->count();
        @endphp

        <!-- Total Agences -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Total Agences</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">{{ $totalAgences }}</h3>
                        <span class="text-success small fw-medium"><i class="fa-solid fa-building me-1"></i>Enregistrées</span>
                    </div>
                    <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-building"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agences Actives -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Agences Actives</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">{{ $actives }}</h3>
                        <span class="text-success small fw-medium"><i class="fa-solid fa-circle-check me-1"></i>En ligne</span>
                    </div>
                    <div class="icon-shape bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-building-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agences Inactives -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Inactives</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">{{ $inactives }}</h3>
                        <span class="text-warning small fw-medium"><i class="fa-solid fa-clock me-1"></i>Hors ligne</span>
                    </div>
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-building-circle-exclamation"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Villes / Couverture -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Villes Couvertes</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">{{ $villes }}</h3>
                        <span class="text-info small fw-medium"><i class="fa-solid fa-location-dot me-1"></i>Localisations</span>
                    </div>
                    <div class="icon-shape bg-info bg-opacity-10 text-info">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- DATATABLE DES AGENCES -->
    <div class="card shadow-sm border-0 bg-white">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold text-orion-dark">Liste des Agences</h5>
            <div class="btn-toolbar">
                <button type="button" class="btn btn-custom btn-custom-sm me-2">
                    <i class="fa-solid fa-download me-1"></i> Exporter
                </button>
                <button type="button" class="btn btn-custom-outline btn-custom-outline-sm" data-bs-toggle="modal" data-bs-target="#createAgencyModal">
                    <i class="fa-solid fa-plus me-1"></i> Nouvelle Agence
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="agenciesTable" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th>#ID</th>
                            <!-- <th>Code</th> -->
                            <th>Nom Agence</th>
                            <th>Ville / Emplacement</th>
                            <th>Responsable</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agences as $agence)
                        <tr>
                            <td><strong>{{ $loop->iteration }}</strong></td>

                            <td>
                                <div class="fw-bold">{{ $agence->nom }}</div>
                                <span class="text-muted small">{{ $agence->email }}</span>
                            </td>
                            <td>{{ $agence->ville ?? '—' }}</td>
                            <td>{{ $agence->responsable ?? '—' }}</td>
                            <td>{{ $agence->telephone ?? '—' }}</td>
                            <td>
                                @if($agence->statut)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary me-1 btn-show-agence" data-id="{{ $agence->id }}" title="Voir les détails">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary me-1" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Supprimer">
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

<!-- MODAL CREATION D'AGENCE -->
<div class="modal fade" id="createAgencyModal" tabindex="-1" aria-labelledby="createAgencyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #212529;">
                <h5 class="modal-title fw-bold" id="createAgencyModalLabel">
                    <i class="fa-solid fa-building-circle-plus me-2" style="color: #fff;"></i>Créer une Nouvelle Agence
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('agencies.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Nom Agence -->
                        <div class="col-md-12">
                            <label for="nom" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nom de l'agence <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-building"></i>
                                <input type="text" id="nom" name="nom" placeholder="Ex: Agence Lomé Ouest" value="{{ old('nom') }}" required>
                            </div>
                            @error('nom') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Adresse Email <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-envelope"></i>
                                <input type="email" id="email" name="email" placeholder="agence@orion.com" value="{{ old('email') }}" required>
                            </div>
                            @error('email') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Téléphone -->
                        <div class="col-md-6">
                            <label for="telephone" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Téléphone</label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-phone"></i>
                                <input type="text" id="telephone" name="telephone" placeholder="+228 90 00 00 00" value="{{ old('telephone') }}">
                            </div>
                            @error('telephone') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Ville -->
                        <div class="col-md-6">
                            <label for="ville" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Ville</label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-location-dot"></i>
                                <input type="text" id="ville" name="ville" placeholder="Ex: Lomé" value="{{ old('ville') }}">
                            </div>
                            @error('ville') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Nom du Responsable -->
                        <div class="col-md-6">
                            <label for="responsable" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Responsable de l'agence</label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" id="responsable" name="responsable" placeholder="Nom et Prénom" value="{{ old('responsable') }}">
                            </div>
                            @error('responsable') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="url" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">URL du site web de l'agence</label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-link"></i>
                                <input type="url" id="url" name="url" placeholder="https://www.exemple.com" value="{{ old('url') }}">
                            </div>
                            @error('url') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="col-12">
                            <label for="adresse" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Adresse / Localisation</label>
                            <div class="input-group-custom" style="align-items: flex-start;">
                                <i class="fa-solid fa-location-dot" style="margin-top: 10px;"></i>
                                <textarea id="adresse" name="adresse" rows="2" placeholder="Rue, quartier, repère..." style="resize: vertical;">{{ old('adresse') }}</textarea>
                            </div>
                            @error('adresse') <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</small> @enderror
                        </div>

                        <!-- Statut -->
                        <div class="col-md-12">
                            <label for="statut" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Statut Initial</label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-toggle-on"></i>
                                <select id="statut" name="statut">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-custom-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-custom">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Enregistrer l'agence
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DETAIL AGENCE -->
<div class="modal fade" id="showAgencyModal" tabindex="-1" aria-labelledby="showAgencyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #212529;">
                <h5 class="modal-title fw-bold" id="showAgencyModalLabel">
                    <i class="fa-solid fa-building me-2" style="color: #fff;"></i>Détails de l'agence : <span id="detail-nom-title"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    <!-- Nom Agence -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nom de l'agence</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-building"></i>
                            <input type="text" id="detail-nom" readonly style="background-color: #f8f9fa; cursor: not-allowed;">
                        </div>
                    </div>

                    <!-- Code Agence -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Code Agence</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-barcode"></i>
                            <input type="text" id="detail-code" readonly style="background-color: #f8f9fa; cursor: not-allowed;" class="fw-bold">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Adresse Email</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" id="detail-email" readonly style="background-color: #f8f9fa; cursor: not-allowed;">
                        </div>
                    </div>

                    <!-- Téléphone -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Téléphone</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-phone"></i>
                            <input type="text" id="detail-telephone" readonly style="background-color: #f8f9fa; cursor: not-allowed;">
                        </div>
                    </div>

                    <!-- Ville -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Ville</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-location-dot"></i>
                            <input type="text" id="detail-ville" readonly style="background-color: #f8f9fa; cursor: not-allowed;">
                        </div>
                    </div>

                    <!-- Responsable -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Responsable de l'agence</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" id="detail-responsable" readonly style="background-color: #f8f9fa; cursor: not-allowed;">
                        </div>
                    </div>

                    <!-- Clé API -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Clé API</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-key"></i>
                            <input type="text" id="detail-cle-api" readonly style="background-color: #f8f9fa; cursor: not-allowed; font-family: monospace;" class="small">
                        </div>
                    </div>

                    <!-- Site Web (URL) -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">URL du site web de l'agence</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-link"></i>
                            <div class="w-100 d-flex align-items-center ps-2 pe-3" style="min-height: 38px; background-color: #f8f9fa;">
                                <a href="#" target="_blank" id="detail-url" class="text-decoration-none text-break small">—</a>
                            </div>
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Adresse / Localisation</label>
                        <div class="input-group-custom" style="align-items: flex-start;">
                            <i class="fa-solid fa-location-dot" style="margin-top: 10px;"></i>
                            <textarea id="detail-adresse" rows="2" readonly style="resize: none; background-color: #f8f9fa; cursor: not-allowed;"></textarea>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Statut Actuel</label>
                        <div class="input-group-custom">
                            <!-- <i class="fa-solid fa-toggle-on"></i> -->
                            <div class="w-100 d-flex align-items-center ps-2" id="detail-statut-container" style="min-height: 38px; background-color: #f8f9fa;">
                                <span id="detail-statut"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques associées -->
                    <div class="col-md-6 mt-3">
                        <div class="p-3 border rounded bg-light text-center">
                            <span class="text-muted small fw-semibold text-uppercase d-block mb-1">
                                <i class="fa-solid fa-file-contract me-1"></i>Souscriptions
                            </span>
                            <h4 class="mb-0 fw-bold text-dark" id="detail-subscriptions-count">0</h4>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="p-3 border rounded bg-light text-center">
                            <span class="text-muted small fw-semibold text-uppercase d-block mb-1">
                                <i class="fa-solid fa-ticket me-1"></i>Tickets Support
                            </span>
                            <h4 class="mb-0 fw-bold text-dark" id="detail-tickets-count">0</h4>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-custom-outline" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialisation de DataTables
        $('#agenciesTable').DataTable({
            responsive: true,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json"
            },
            columnDefs: [{
                orderable: false,
                targets: -1
            }]
        });

        // Réouverture automatique de la modal en cas d'erreur de validation
        @if($errors->any())
        $('#createAgencyModal').modal('show');
        @endif
    });

    $(document).on('click', '.btn-show-agence', function() {
        let agenceId = $(this).data('id');
        let url = "{{ route('agencies.show', ':id') }}".replace(':id', agenceId);

        // Requête AJAX pour récupérer les données de l'agence
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Titre du modal (élément <span> classique -> .text())
                $('#detail-nom-title').text(data.nom ?? '—');

                // Inputs et Textarea -> utiliser .val()
                $('#detail-nom').val(data.nom ?? '—');
                $('#detail-code').val(data.code_agence ?? '—');
                $('#detail-cle-api').val(data.cle_api ?? 'Non générée');
                $('#detail-email').val(data.email ?? '—');
                $('#detail-telephone').val(data.telephone ?? '—');
                $('#detail-responsable').val(data.responsable ?? '—');
                $('#detail-ville').val(data.ville ?? '—');
                $('#detail-adresse').val(data.adresse ?? '—');

                // URL
                if (data.url) {
                    $('#detail-url').attr('href', data.url).text(data.url);
                } else {
                    $('#detail-url').removeAttr('href').text('—');
                }

                // Statut avec badge (élément <span> classique -> .html())
                if (parseInt(data.statut) === 1) {
                    $('#detail-statut').html('<span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Active</span>');
                } else {
                    $('#detail-statut').html('<span class="badge bg-danger"><i class="fa-solid fa-circle-xmark me-1"></i>Inactive</span>');
                }

                // Nombre de souscriptions et tickets (éléments <h4>/<span> -> .text())
                $('#detail-subscriptions-count').text(data.soubscriptions_count ?? 0);
                $('#detail-tickets-count').text(data.tickets_count ?? 0);

                // Ouverture du modal
                $('#showAgencyModal').modal('show');
            },
            error: function() {
                alert("Impossible de charger les détails de l'agence.");
            }
        });
    });
</script>
@endpush
