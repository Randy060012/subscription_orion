<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord ORION TECHNOLOGIES SARL</title>

    <!-- Google Fonts: Ubuntu -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* --- COULEURS DU LOGO (Palette d'Orion Technologies) --- */
        :root {
            /* Bleu vif du logo */
            --orion-blue: #2E81E9;
            /* Bleu principal */
            --orion-blue-hover: #1A6FD6;
            /* Bleu au survol */

            /* Noir très foncé du logo */
            --orion-dark: #12151B;
            /* Noir principal */
            --orion-dark-alt: #1E222A;
            /* Noir plus clair (pour sous-menus, etc.) */

            /* Couleurs d'accentuation */
            --orion-light: #F8F9FA;
            /* Fond clair */
            --orion-white: #FFFFFF;
            /* Blanc */
            --orion-gray-muted: rgba(255, 255, 255, 0.7);
            /* Texte atténué */
        }

        body {
            background-color: var(--orion-light);
            font-family: 'Ubuntu', sans-serif;
            color: var(--orion-dark);
        }

        /* --- STYLES DE LA SIDEBAR --- */
        .sidebar {
            min-height: 100vh;
            background-color: var(--orion-dark);
            color: var(--orion-white);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link {
            color: var(--orion-gray-muted);
            padding: 0.75rem 1.25rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            font-weight: 400;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: var(--orion-white);
            background-color: var(--orion-blue);
        }

        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 0.5rem;
        }

        /* --- STYLES DES CARTES DE STATISTIQUES --- */
        .stat-card {
            border: none;
            border-radius: 0.75rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
        }

        .icon-shape {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        /* Utilisation du bleu du logo pour les éléments bleus */
        .bg-primary.bg-opacity-10 {
            background-color: rgba(46, 129, 233, 0.1) !important;
        }

        .text-primary {
            color: var(--orion-blue) !important;
        }

        .btn-primary {
            background-color: var(--orion-blue);
            border-color: var(--orion-blue);
        }

        .btn-primary:hover {
            background-color: var(--orion-blue-hover);
            border-color: var(--orion-blue-hover);
        }

        .btn-outline-primary {
            color: var(--orion-blue);
            border-color: var(--orion-blue);
        }

        .btn-outline-primary:hover {
            color: var(--orion-white);
            background-color: var(--orion-blue-hover);
            border-color: var(--orion-blue-hover);
        }

        /* ===============================================================
           STYLES HÉRITÉS DE LA PAGE LOGIN (Design System Unifié)
           =============================================================== */

        /* Divider line – comme sur la page login */
        .divider-line {
            border-top: 1px solid #dee2e6;
            margin: 35px 0;
        }

        .divider-line-light {
            border-top: 1px solid #dee2e6;
            margin: 15px 0;
        }

        /* Input avec bordure inférieure uniquement (login style) */
        .input-group-custom {
            position: relative;
            border-bottom: 1.5px solid #495057;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .input-group-custom i {
            font-size: 1.1rem;
            color: #495057;
            width: 30px;
        }

        .input-group-custom input,
        .input-group-custom select,
        .input-group-custom textarea {
            background: transparent;
            border: none;
            outline: none;
            box-shadow: none;
            color: #212529;
            width: 100%;
            padding: 8px 10px;
            font-size: 0.95rem;
        }

        .input-group-custom input::placeholder,
        .input-group-custom textarea::placeholder {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .input-group-custom input:focus,
        .input-group-custom select:focus,
        .input-group-custom textarea:focus {
            background: transparent;
            box-shadow: none;
        }

        /* Bouton custom (login style) */
        .btn-custom {
            background-color: #212529;
            color: #ffffff;
            border: none;
            border-radius: 0px;
            padding: 12px 24px;
            font-weight: 600;
            letter-spacing: 2px;
            font-size: 0.9rem;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-custom:hover {
            background-color: #343a40;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-custom-sm {
            padding: 8px 16px;
            font-size: 0.8rem;
            letter-spacing: 1.5px;
        }

        .btn-custom-outline {
            background-color: transparent;
            color: #212529;
            border: 1.5px solid #212529;
            border-radius: 0px;
            padding: 12px 24px;
            font-weight: 600;
            letter-spacing: 2px;
            font-size: 0.9rem;
            transition: background-color 0.3s, color 0.3s, transform 0.2s;
        }

        .btn-custom-outline:hover {
            background-color: #212529;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-custom-outline-sm {
            padding: 8px 16px;
            font-size: 0.8rem;
            letter-spacing: 1.5px;
        }

        /* Checkbox custom (login style) */
        .form-check-input-custom {
            background-color: transparent;
            border: 1.5px solid #495057;
            border-radius: 2px;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .form-check-input-custom:checked {
            background-color: #212529;
            border-color: #212529;
        }

        /* Lien style login */
        .auth-link {
            color: #6c757d;
            text-decoration: none;
            font-style: italic;
            font-size: 0.88rem;
            transition: color 0.2s;
        }

        .auth-link:hover {
            color: #212529;
        }

        /* Titre page style login */
        .page-title-login {
            font-weight: 300;
            font-size: 2rem;
            letter-spacing: 1px;
            color: #212529;
        }

        /* Badges sombres custom */
        .badge-custom-dark {
            background-color: #212529;
            color: #ffffff;
        }

        .badge-custom-outline {
            background-color: transparent;
            color: #212529;
            border: 1px solid #212529;
        }

        /* ===============================================================
           ANIMATIONS & MICRO-INTERACTIONS
           =============================================================== */

        /* --- KEYFRAMES --- */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.92);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.15); }
        }

        @keyframes borderGrow {
            from {
                width: 0%;
                opacity: 0;
            }
            to {
                width: 100%;
                opacity: 1;
            }
        }

        /* --- CONTENU PRINCIPAL : ENTRÉE --- */
        main[class*="col-md-9"] {
            animation: fadeInUp 0.5s ease-out both;
        }

        /* --- SIDEBAR : ENTRÉE --- */
        .sidebar {
            animation: slideInLeft 0.4s ease-out both;
        }

        .sidebar .nav-item {
            opacity: 0;
            animation: fadeInUp 0.4s ease-out forwards;
        }

        .sidebar .nav-item:nth-child(1) { animation-delay: 0.05s; }
        .sidebar .nav-item:nth-child(2) { animation-delay: 0.10s; }
        .sidebar .nav-item:nth-child(3) { animation-delay: 0.15s; }
        .sidebar .nav-item:nth-child(4) { animation-delay: 0.20s; }
        .sidebar .nav-item:nth-child(5) { animation-delay: 0.25s; }

        .sidebar .nav-link {
            position: relative;
            overflow: hidden;
            transition: all 0.25s ease;
        }

        .sidebar .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background: var(--orion-blue);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* --- STAT CARDS : ENTRÉE STAGGER --- */
        .stat-card {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .row.g-3.mb-4 > [class*="col-"]:nth-child(1) .stat-card { animation-delay: 0.05s; }
        .row.g-3.mb-4 > [class*="col-"]:nth-child(2) .stat-card { animation-delay: 0.10s; }
        .row.g-3.mb-4 > [class*="col-"]:nth-child(3) .stat-card { animation-delay: 0.15s; }
        .row.g-3.mb-4 > [class*="col-"]:nth-child(4) .stat-card { animation-delay: 0.20s; }

        .stat-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 0.8rem 1.5rem rgba(0, 0, 0, 0.1) !important;
        }

        .icon-shape {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .stat-card:hover .icon-shape {
            transform: scale(1.1) rotate(3deg);
        }

        /* --- AUTRES CARTES (non stat-card) : ANIMATION D'ENTRÉE --- */
        .card.shadow-sm:not(.stat-card) {
            animation: scaleIn 0.45s ease-out forwards;
        }

        .card.shadow-sm:not(.stat-card):nth-of-type(1) { animation-delay: 0.10s; }
        .card.shadow-sm:not(.stat-card):nth-of-type(2) { animation-delay: 0.18s; }
        .card.shadow-sm:not(.stat-card):nth-of-type(3) { animation-delay: 0.26s; }

        .card-header {
            transition: background-color 0.25s ease;
        }

        /* --- BOUTONS : MICRO-INTERACTIONS --- */
        .btn-custom {
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(33, 37, 41, 0.25);
        }

        .btn-custom:active {
            transform: translateY(0px) scale(0.98);
            transition-duration: 0.05s;
        }

        /* Shine subtil sur les boutons custom */
        .btn-custom::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
            transition: left 0.5s ease;
            pointer-events: none;
        }

        .btn-custom:hover::after {
            left: 100%;
        }

        .btn-custom-outline {
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s, color 0.3s, transform 0.2s, box-shadow 0.3s;
        }

        .btn-custom-outline:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(33, 37, 41, 0.15);
        }

        .btn-custom-outline:active {
            transform: translateY(0px) scale(0.98);
            transition-duration: 0.05s;
        }

        /* --- INPUTS : FOCUS ANIMATION --- */
        .input-group-custom {
            position: relative;
            transition: border-color 0.3s ease;
        }

        .input-group-custom::after {
            content: '';
            position: absolute;
            bottom: -1.5px;
            left: 50%;
            width: 0%;
            height: 2px;
            background: #212529;
            transition: width 0.35s ease, left 0.35s ease;
        }

        .input-group-custom:focus-within::after {
            width: 100%;
            left: 0%;
        }

        .input-group-custom:focus-within i {
            color: #212529;
            transition: color 0.3s ease;
        }

        /* --- TABLE ROWS : HOVER LIFT --- */
        .table-hover tbody tr {
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .table-hover tbody tr:hover {
            transform: scale(1.005);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        /* --- DIVIDER LINE : ANIMÉE --- */
        .divider-line-light {
            position: relative;
            overflow: hidden;
            border: none;
            height: 1px;
            background: #dee2e6;
            margin: 15px 0;
        }

        .divider-line-light::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: linear-gradient(90deg, transparent, #212529, transparent);
            animation: shimmer 2.5s ease-in-out infinite;
            background-size: 200% 100%;
            opacity: 0.15;
        }

        /* --- MODAL : OPENING ANIMATION --- */
        .modal.fade .modal-dialog {
            transform: scale(0.92) translateY(20px);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            transition: background-color 0.3s ease;
        }

        /* --- BADGE : TINY ANIMATION --- */
        .badge {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        /* --- PAGE TITLE : FADE-IN --- */
        .page-title-login {
            animation: fadeInDown 0.4s ease-out both;
        }

        /* --- RÉDUCTION DE MOUVEMENT (ACCESSIBILITÉ) --- */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
            }

            .stat-card:hover {
                transform: translateY(-2px) !important;
            }

            .btn-custom:hover,
            .btn-custom-outline:hover {
                transform: none !important;
                box-shadow: none !important;
            }

            .table-hover tbody tr:hover {
                transform: none !important;
                box-shadow: none !important;
            }

            .modal.fade .modal-dialog {
                transform: none !important;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <div class="container-fluid p-0">
        <div class="row g-0">

            <!-- SIDEBAR -->
            @include('layouts.sidebar')

            <!-- CONTENU PRINCIPAL -->
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Config globale pour appliquer la police Ubuntu sur Chart.js
        Chart.defaults.font.family = "'Ubuntu', sans-serif";

        // 1. Graphique d'évolution des souscriptions (Bleu du logo pour la ligne)
        const ctxSub = document.getElementById('subscriptionsChart').getContext('2d');
        new Chart(ctxSub, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil'],
                datasets: [{
                    label: 'Souscriptions',
                    data: [12, 19, 15, 25, 22, 30, 38],
                    borderColor: '#2E81E9', // Bleu vif du logo
                    backgroundColor: 'rgba(46, 129, 233, 0.08)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 2. Graphique des tickets par statut (Bleu du logo pour un segment)
        const ctxTicket = document.getElementById('ticketsChart').getContext('2d');
        new Chart(ctxTicket, {
            type: 'doughnut',
            data: {
                labels: ['Nouveaux', 'En cours', 'Résolus'],
                datasets: [{
                    data: [5, 8, 20],
                    backgroundColor: ['#2E81E9', '#ffc107', '#198754'] // Bleu vif du logo, Jaune, Vert
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
