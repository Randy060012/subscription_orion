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

    <!-- CARTES STATISTIQUES (KPI) -->
    <div class="row g-3 mb-4">
        <!-- Total Abonnés -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Abonnés Actifs</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">342</h3>
                        <span class="text-success small fw-medium"><i class="fa-solid fa-arrow-up me-1"></i>+12% ce mois</span>
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
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">1.850.000 FCFA</h3>
                        <span class="text-success small fw-medium"><i class="fa-solid fa-chart-line me-1"></i>En hausse</span>
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
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">18</h3>
                        <span class="text-warning small fw-medium"><i class="fa-solid fa-bell me-1"></i>Relance requise</span>
                    </div>
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forfaits Inactifs -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Abonnements Expirés</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">45</h3>
                        <span class="text-danger small fw-medium"><i class="fa-solid fa-circle-xmark me-1"></i>Inactifs</span>
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
                        <tr>
                            <td class="text-nowrap"><strong>1</strong></td>
                            <td>
                                <div class="fw-bold">Koffi Mensah</div>
                                <span class="text-muted small">+228 90 11 22 33</span>
                            </td>
                            <td class="text-nowrap"><span class="badge bg-light text-dark border">Pass Mensuel VIP</span></td>
                            <td class="text-nowrap">01/05/2026</td>
                            <td class="text-nowrap">31/05/2026</td>
                            <td class="fw-bold text-orion-dark text-nowrap">45 000 FCFA</td>
                            <td class="text-nowrap"><span class="badge bg-success">Actif</span></td>
                            <td class="text-end text-nowrap">
                                <div class="action-btns">
                                    <button class="btn btn-sm btn-outline-secondary" title="Voir"><i class="fa-solid fa-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-primary" title="Modifier"><i class="fa-solid fa-pen"></i></button>
                                    <button class="btn btn-sm btn-outline-danger" title="Annuler"><i class="fa-solid fa-ban"></i></button>
                                </div>
                            </td>
                        </tr>
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
            <form action="#" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nom du Client <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" placeholder="Nom et prénom" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Téléphone <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-phone"></i>
                                <input type="text" placeholder="+228 90 00 00 00" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Forfait choisit <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-tag"></i>
                                <select required>
                                    <option value="mensuel_std">Pass Mensuel Standard</option>
                                    <option value="mensuel_vip">Pass Mensuel VIP</option>
                                    <option value="annuel">Forfait Annuel</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Montant (FCFA) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-money-bill"></i>
                                <input type="number" placeholder="Ex: 45000" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Date de début <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-calendar"></i>
                                <input type="date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Date de fin <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-calendar"></i>
                                <input type="date" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-custom-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-custom"><i class="fa-solid fa-floppy-disk me-1"></i> Créer l'abonnement</button>
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
                language: { url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json" },
                columnDefs: [
                    { orderable: false, targets: 7 },
                    { className: "text-nowrap", targets: [0, 2, 3, 4, 5, 6, 7] }
                ]
            });
        });
    </script>
@endpush
