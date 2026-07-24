@extends('layouts.master')

@section('content')

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

    <!-- HEADER DE PAGE (style login) -->
    <div class="divider-line-light"></div>
    <div class="mb-3">
        <h1 class="page-title-login mb-0">Mon Profil & Sécurité</h1>
        <p class="text-muted small mb-0 mt-1">Gérez vos informations personnelles, votre identifiant et votre mot de passe.</p>
    </div>
    <div class="divider-line-light"></div>

    <!-- MESSAGES DE NOTIFICATION -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> Veuillez corriger les erreurs ci-dessous.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- CARTE RECAPITULATIVE PROFIL (COLONNE GAUCHE) -->
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 bg-white text-center p-4">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 100px; height: 100px;">
                            <i class="fa-solid fa-user-gear fs-1"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-orion-dark mb-1">Administrateur</h4>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 mb-3">
                        <i class="fa-solid fa-shield-halved me-1"></i> Administrateur Systèmes
                    </span>
                    <hr class="text-secondary opacity-25">
                    <div class="text-start small text-muted">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fa-solid fa-envelope me-2"></i>Email :</span>
                            <strong class="text-dark">admin@orion.tg</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fa-solid fa-user me-2"></i>Username :</span>
                            <strong class="text-dark">admin</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="fa-solid fa-calendar-day me-2"></i>Membre depuis :</span>
                            <strong class="text-dark">01/01/2026</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORMULAIRES DE MODIFICATION (COLONNE DROITE) -->
        <div class="col-12 col-lg-8">

            <!-- FORMULAIRE 1 : INFORMATIONS GENERALES -->
            <div class="card shadow-sm border-0 bg-white mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <i class="fa-solid fa-user-pen text-primary me-2 fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold text-orion-dark">Modifier mes Informations</h5>
                </div>
                <div class="card-body p-4">
                    <form action="#" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Nom d'utilisateur (Username) -->
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nom d'utilisateur (Username) <span class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <i class="fa-solid fa-at"></i>
                                    <input type="text"
                                           id="username"
                                           name="username"
                                           placeholder="Nom d'utilisateur"
                                           required>
                                </div>
                                @error('username')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Adresse Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Adresse E-mail <span class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <i class="fa-solid fa-envelope"></i>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           placeholder="adresse@email.com"
                                           required>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-custom">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FORMULAIRE 2 : CHANGER LE MOT DE PASSE -->
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <i class="fa-solid fa-key text-warning me-2 fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold text-orion-dark">Changer le Mot de Passe</h5>
                </div>
                <div class="card-body p-4">
                    <form action="#" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Mot de passe actuel -->
                            <div class="col-12">
                                <label for="current_password" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Mot de passe actuel <span class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <i class="fa-solid fa-lock"></i>
                                    <input type="password"
                                           id="current_password"
                                           name="current_password"
                                           placeholder="••••••••"
                                           required>
                                </div>
                                @error('current_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nouveau mot de passe -->
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Nouveau mot de passe <span class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <i class="fa-solid fa-key"></i>
                                    <input type="password"
                                           id="password"
                                           name="password"
                                           placeholder="Au moins 8 caractères"
                                           required>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirmation du mot de passe -->
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold" style="font-size: 0.85rem; color: #495057;">Confirmer le nouveau mot de passe <span class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <i class="fa-solid fa-check-double"></i>
                                    <input type="password"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Répétez le mot de passe"
                                           required>
                                </div>
                            </div>

                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-custom-outline">
                                    <i class="fa-solid fa-shield-halved me-1"></i> Mettre à jour le mot de passe
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</main>

@endsection
