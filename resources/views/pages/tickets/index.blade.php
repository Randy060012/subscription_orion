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
    #ticketsTable {
        width: 100% !important;
    }

    /* Limite la largeur de la colonne sujet pour éviter d'élargir exagérément le tableau */
    .td-truncate {
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
        <h1 class="page-title-login mb-0">Gestion des Tickets d'Assistance</h1>
        <p class="text-muted small mb-0 mt-1">Suivi des demandes des agences : bugs, améliorations, nouveaux modules et formations.</p>
    </div>
    <div class="divider-line-light"></div>

    <!-- CARTES STATISTIQUES (KPI) -->
    <div class="row g-3 mb-4">
        <!-- Tickets ouverts / Total du jour -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Tickets du jour</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">18</h3>
                        <span class="text-primary small fw-medium"><i class="fa-solid fa-arrow-up me-1"></i>+5 aujourd'hui</span>
                    </div>
                    <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets En Cours -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">En Cours</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">7</h3>
                        <span class="text-info small fw-medium"><i class="fa-solid fa-spinner me-1"></i>En traitement</span>
                    </div>
                    <div class="icon-shape bg-info bg-opacity-10 text-info">
                        <i class="fa-solid fa-gears"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- En Attente de Prise en Charge -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">En attente</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">4</h3>
                        <span class="text-warning small fw-medium"><i class="fa-solid fa-clock me-1"></i>Non assignés</span>
                    </div>
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Résolus -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Résolus</span>
                        <h3 class="fw-bold mb-0 mt-1 text-orion-dark">85</h3>
                        <span class="text-success small fw-medium"><i class="fa-solid fa-circle-check me-1"></i>Ce mois</span>
                    </div>
                    <div class="icon-shape bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DATATABLE DES TICKETS -->
    <div class="card shadow-sm border-0 bg-white">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title mb-0 fw-bold text-orion-dark">Liste des Tickets Agences</h5>
            <div class="btn-toolbar text-nowrap">
                <button type="button" class="btn btn-custom btn-custom-sm me-2">
                    <i class="fa-solid fa-download me-1"></i> Exporter
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="ticketsTable" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap">#Réf Ticket</th>
                            <th>Sujet / Demande</th>
                            <th class="text-nowrap">Type</th>
                            <th>Agence Demandeuse</th>
                            <th class="text-nowrap">Priorité</th>
                            <th class="text-nowrap">Statut</th>
                            <th class="text-nowrap">Date de création</th>
                            <th class="text-end text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                        <tr>
                            <td class="text-nowrap"><strong class="text-primary">{{ $ticket->code }}</strong></td>
                            <td class="td-truncate" title="{{ $ticket->sujet }}">
                                <div class="fw-bold text-truncate">{{ $ticket->sujet }}</div>
                                <span class="text-muted small d-block text-truncate">Demandeur : {{ $ticket->nom_demandeur }}</span>
                            </td>
                            <td class="text-nowrap">
                                @switch($ticket->type)
                                @case('bug')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25"><i class="fa-solid fa-bug me-1"></i>Bug</span>
                                @break
                                @case('formation')
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25"><i class="fa-solid fa-graduation-cap me-1"></i>Formation</span>
                                @break
                                @case('amelioration')
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25"><i class="fa-solid fa-lightbulb me-1"></i>Amélioration</span>
                                @break
                                @default
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25"><i class="fa-solid fa-tag me-1"></i>{{ ucfirst($ticket->type) }}</span>
                                @endswitch
                            </td>
                            <td class="text-nowrap">{{ $ticket->agence->nom ?? 'Agence inconnue' }}</td>
                            <td class="text-nowrap">
                                @switch($ticket->priorite)
                                @case('urgente')
                                @case('haute')
                                <span class="badge bg-danger">{{ ucfirst($ticket->priorite) }}</span>
                                @break
                                @case('moyenne')
                                <span class="badge bg-secondary">{{ ucfirst($ticket->priorite) }}</span>
                                @break
                                @default
                                <span class="badge bg-success">{{ ucfirst($ticket->priorite) }}</span>
                                @endswitch
                            </td>
                            <td class="text-nowrap">
                                @switch($ticket->statut)
                                @case('en_cours')
                                <span class="badge bg-warning text-dark">En cours</span>
                                @break
                                @case('en_attente')
                                <span class="badge bg-info text-dark">En attente</span>
                                @break
                                @case('resolu')
                                <span class="badge bg-success">Résolu</span>
                                @break
                                @default
                                <span class="badge bg-dark">Fermé</span>
                                @endswitch
                            </td>
                            <td class="text-nowrap">{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="text-end text-nowrap">
                                <div class="action-btns">
                                    <button class="btn btn-sm btn-outline-secondary" title="Voir détails"><i class="fa-solid fa-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-primary edit-ticket-btn"
                                        title="Modifier"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editTicketModal"
                                        data-id="{{ $ticket->id }}"
                                        data-code="{{ $ticket->code }}"
                                        data-priorite="{{ $ticket->priorite }}"
                                        data-statut="{{ $ticket->statut }}"
                                        data-reponse="{{ $ticket->reponse }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Fermer / Archiver"><i class="fa-solid fa-box-archive"></i></button>
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

<!-- MODAL CREATION DE TICKET -->
<div class="modal fade" id="createTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #212529;">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-headset me-2" style="color: #fff;"></i>Créer un Ticket de Support
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Agence Concernée <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-building"></i>
                                <select required>
                                    <option value="" selected disabled>Choisir l'agence...</option>
                                    <option value="1">Agence Lomé Centre</option>
                                    <option value="2">Agence Kara</option>
                                    <option value="3">Agence Kpalimé</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Type de Demande <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-tag"></i>
                                <select required>
                                    <option value="" selected disabled>Sélectionner le type...</option>
                                    <option value="bug">Bug / Anomalie</option>
                                    <option value="amelioration">Amélioration</option>
                                    <option value="nouveau_module">Nouveau Module</option>
                                    <option value="formation">Besoin de Formation</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nom du Demandeur <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" placeholder="Nom de la personne" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Priorité <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-flag"></i>
                                <select required>
                                    <option value="basse">Basse</option>
                                    <option value="moyenne" selected>Moyenne</option>
                                    <option value="haute">Haute</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Sujet de la demande <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-heading"></i>
                                <input type="text" placeholder="Résumé succinct du problème ou de la demande" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Description détaillée <span class="text-danger">*</span></label>
                            <div class="input-group-custom" style="align-items: flex-start;">
                                <i class="fa-solid fa-align-left" style="margin-top: 10px;"></i>
                                <textarea rows="4" placeholder="Détaillez la demande, les étapes pour reproduire le bug ou les besoins spécifiques..." style="resize: vertical;" required></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Pièce jointe / Capture d'écran</label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-paperclip"></i>
                                <input type="file">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-custom-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-custom"><i class="fa-solid fa-paper-plane me-1"></i> Soumettre le Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT TICKET -->
<!-- <div class="modal fade" id="editTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #212529;">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-pen-to-square me-2" style="color: #fff;"></i>Mettre à jour le Ticket <span id="edit-ticket-code"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="edit-ticket-form" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="modal-body p-4">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Priorité</label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-flag"></i>
                                <select name="priorite" id="edit-priorite" required>
                                    <option value="basse">Basse</option>
                                    <option value="moyenne">Moyenne</option>
                                    <option value="haute">Haute</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Statut</label>
                            <div class="input-group-custom">
                                <i class="fa-solid fa-list-check"></i>
                                <select name="statut" id="edit-statut" required>
                                    <option value="en_attente">En attente</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="resolu">Résolu</option>
                                    <option value="ferme">Fermé</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Réponse / Solution apportée</label>
                            <div class="input-group-custom" style="align-items: flex-start;">
                                <i class="fa-solid fa-reply" style="margin-top: 10px;"></i>
                                <textarea name="reponse" id="edit-reponse" rows="4" placeholder="Expliquez la résolution du problème ou ajoutez un commentaire d'avancement..." style="resize: vertical;"></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<!-- Modal Résolution de Ticket -->
<div class="modal fade" id="resoudreTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-check-circle me-2"></i>Résoudre le Ticket
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="resoudreTicketForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="commentaire" class="form-label fw-semibold">Commentaire de résolution <span class="text-danger">*</span></label>
                        <textarea name="commentaire" id="commentaire" class="form-control" rows="4" placeholder="Expliquez la solution apportée..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-check me-1"></i> Valider et Résoudre
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
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#ticketsTable').DataTable({
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
                    targets: [0, 2, 4, 5, 6, 7]
                }
            ]
        });
    });

    // $(document).ready(function() {
    //     $('.edit-ticket-btn').on('click', function() {
    //         var id = $(this).data('id');
    //         var code = $(this).data('code');
    //         var priorite = $(this).data('priorite');
    //         var statut = $(this).data('statut');
    //         var reponse = $(this).data('reponse');

    //         $('#edit-ticket-code').text('#' + code);
    //         $('#edit-priorite').val(priorite);
    //         $('#edit-statut').val(statut);
    //         $('#edit-reponse').val(reponse);

    //         // Mise à jour dynamique de l'action du formulaire
    //         $('#edit-ticket-form').attr('action', '/tickets/' + id);
    //     });
    // });

    $(document).on('click', '.btn-resoudre-ticket', function() {
        let ticketId = $(this).data('id');
        let resoudreUrl = "{{ route('tickets.resoudre', ':id') }}".replace(':id', ticketId);

        $('#resoudreTicketForm').attr('action', resoudreUrl);
        $('#resoudreTicketModal').modal('show');
    });
</script>
@endpush
