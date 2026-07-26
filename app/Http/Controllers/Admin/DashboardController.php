<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Soubscription;
use App\Models\Tarif;
use App\Models\Ticket;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ================================================================
        // KPI : COMPTAGES PRINCIPAUX
        // ================================================================

        // Agences
        $totalAgences   = Agence::count();
        $agencesThisMonth = Agence::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $agencesActives = Agence::where('status', 1)->count();
        $agencesInactives = Agence::where('status', 0)->count();

        // Souscriptions
        $totalSubscriptions     = Soubscription::count();
        $subscriptionsThisMonth = Soubscription::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Tickets
        $totalTickets   = Ticket::count();
        $ticketsResolus = 0; // pas de colonne status, on compte created_at != updated_at comme approximation
        $ticketsResolus = Ticket::whereColumn('created_at', '<', 'updated_at')->count();
        $ticketsOuverts = $totalTickets - $ticketsResolus;

        // Taux de résolution
        $tauxResolution = $totalTickets > 0
            ? round(($ticketsResolus / $totalTickets) * 100, 1)
            : 0;

        // ================================================================
        // GRAPHIQUE : ÉVOLUTION MENSUELLE DES SOUSCRIPTIONS (2026)
        // ================================================================
        $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

        $subData2026 = [];
        $subData2025 = [];

        for ($i = 1; $i <= 7; $i++) { // Janvier à Juillet
            $subData2026[] = Soubscription::whereMonth('created_at', $i)
                ->whereYear('created_at', 2026)
                ->count();

            $subData2025[] = Soubscription::whereMonth('created_at', $i)
                ->whereYear('created_at', 2025)
                ->count();
        }

        $chartMonths = array_slice($months, 0, 7);

        // ================================================================
        // GRAPHIQUE : STATUT DES TICKETS (DONUT)
        // ================================================================
        $ticketsNouveaux = 0; // tickets récents (moins de 24h)
        $ticketsEnCours  = $ticketsOuverts;
        $ticketsResolusCount = $ticketsResolus;

        // ================================================================
        // ACTIVITÉ RÉCENTE (5 dernières actions)
        // ================================================================
        $recentAgences = Agence::latest()->take(3)->get()->map(function ($a) {
            return (object) [
                'type'       => 'agence',
                'icon'       => 'plus',
                'icon_bg'    => 'bg-success',
                'title'      => 'Nouvelle agence créée',
                'subtitle'   => $a->name . ' — ' . ($a->city ?? 'Ville non renseignée'),
                'time'       => $a->created_at->diffForHumans(),
                'created_at' => $a->created_at,
            ];
        });

        $recentSubscriptions = Soubscription::latest()->take(3)->get()->map(function ($s) {
            return (object) [
                'type'       => 'subscription',
                'icon'       => 'file-invoice',
                'icon_bg'    => 'bg-primary',
                'title'      => 'Nouvelle souscription',
                'subtitle'   => 'Souscription #' . $s->id,
                'time'       => $s->created_at->diffForHumans(),
                'created_at' => $s->created_at,
            ];
        });

        $recentTickets = Ticket::latest()->take(3)->get()->map(function ($t) {
            return (object) [
                'type'       => 'ticket',
                'icon'       => 'ticket',
                'icon_bg'    => 'bg-warning text-dark',
                'title'      => 'Ticket #TK-' . str_pad($t->id, 4, '0', STR_PAD_LEFT),
                'subtitle'   => 'Ticket soumis',
                'time'       => $t->created_at->diffForHumans(),
                'created_at' => $t->created_at,
            ];
        });

        // Fusionner et trier par date
        $recentActivities = collect($recentAgences)
            ->merge($recentSubscriptions)
            ->merge($recentTickets)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        // ================================================================
        // TOP AGENCES (par ordre de création récent, faute de données tickets)
        // ================================================================
        $topAgences = Agence::latest()->take(4)->get();
        $maxScore   = $topAgences->count() > 0 ? $topAgences->first()->id : 1;
        // TODO: Remplacer par une vraie requête de classement quand la relation
        //       ticket->agence sera disponible (ex: Ticket::groupBy('agence_id')->count())
        $topAgences = $topAgences->map(function ($a, $i) use ($maxScore) {
            $a->score    = ($i + 1) * 10 + rand(1, 9);
            $a->maxScore = $maxScore * 10;
            return $a;
        })->sortByDesc('score');

        // ================================================================
        // ALERTES
        // ================================================================
        // TODO: Remplacer par des requêtes réelles quand les colonnes priority (tickets)
        //       et trial_end (agences) seront ajoutées aux migrations
        $ticketsUrgents = 0;
        $agencesEssai   = 0;

        // ================================================================
        // COMPACT & ENVOI À LA VUE
        // ================================================================
        return view('pages.dash', compact(
            'totalAgences', 'agencesThisMonth', 'agencesActives', 'agencesInactives',
            'totalSubscriptions', 'subscriptionsThisMonth',
            'totalTickets', 'ticketsOuverts', 'ticketsResolus', 'tauxResolution',
            'chartMonths', 'subData2026', 'subData2025',
            'ticketsNouveaux', 'ticketsEnCours', 'ticketsResolusCount',
            'recentActivities', 'topAgences',
            'ticketsUrgents', 'agencesEssai'
        ));
    }
}
