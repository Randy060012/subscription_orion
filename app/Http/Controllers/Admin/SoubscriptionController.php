<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Soubscription;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SoubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $soubscriptions = Soubscription::with(['agence', 'tarif'])->latest()->get();

        $agences = Agence::where('statut', 1)->get();
        $tarifs = Tarif::where('est_actif', 1)->get();

        // Calculs KPI
        $totalActifs = $soubscriptions->where('status', 1)->count();

        $expired = $soubscriptions->filter(function ($s) {
            return $s->isExpired();
        })->count();

        $expiringSoon = $soubscriptions->filter(function ($s) {
            return $s->isExpiringSoon();
        })->count();

        $revenuTotal = $soubscriptions->where('status', 1)->sum(function ($s) {
            return $s->tarif ? $s->tarif->prix : 0;
        });

        return view('pages.soubscriptions.index', compact(
            'agences',
            'tarifs',
            'soubscriptions',
            'totalActifs',
            'expired',
            'expiringSoon',
            'revenuTotal'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation avec prise en compte des nouvelles dates
        $validated = $request->validate([
            'agence_id'  => 'required|exists:agences,id',
            'tarif_id'   => 'required|exists:tarifs,id',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after_or_equal:date_debut',
            'status'     => 'nullable|boolean',
        ]);

        try {
            // Conversion en objets Carbon pour la manipulation
            $dateDebut = Carbon::parse($request->date_debut);
            $dateFin   = Carbon::parse($request->date_fin);

            // 2. Création de la souscription avec les dates choisies
            $soubscription = Soubscription::create([
                'agence_id'  => $request->agence_id,
                'tarif_id'   => $request->tarif_id,
                'date_debut' => $dateDebut,
                'date_fin'   => $dateFin,
                'status'     => $request->boolean('status', true),
            ]);

            // 3. Récupération des relations pour le SMS (si besoin des objets complets)
            $agence = $soubscription->agence;
            $tarif  = $soubscription->tarif;

            // Envoi du SMS de confirmation
            $this->sendSubscriptionConfirmation($agence, $tarif, $dateDebut, $dateFin);

            return redirect()->back()->with('success', 'Souscription créée avec succès. Un SMS de confirmation a été envoyé.');
        } catch (\Throwable $e) {
            Log::error('Erreur lors de la création de souscription : ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
    /**
     * Envoie un SMS de confirmation de souscription au client
     */
    private function sendSubscriptionConfirmation($agence, $tarif, $dateDebut, $dateFin)
    {
        try {
            // Récupérer le numéro de téléphone de l'agence
            $telephone = $agence->telephone;

            if (empty($telephone)) {
                Log::warning('Téléphone de l\'agence non disponible pour l\'envoi du SMS', ['agence_id' => $agence->id]);
                return;
            }

            // Nettoyer le numéro de téléphone
            $telephoneClean = preg_replace('/[^0-9+]/', '', $telephone);

            // Formater les dates pour le message
            $dateDebutFormatted = Carbon::parse($dateDebut)->format('d/m/Y');
            $dateFinFormatted = Carbon::parse($dateFin)->format('d/m/Y');
            $montant = number_format($tarif->montant, 0, ',', ' ') . ' FCFA';

            // Construire le message
            $message = "ASSURIA PRO - CONFIRMATION DE SOUSCRIPTION\n\n";
            $message .= "Cher(e) client(e),\n\n";
            $message .= "Votre souscription au forfait \"{$tarif->nom}\" a été enregistrée avec succès.\n\n";
            $message .= "Détails de votre abonnement :\n";
            $message .= "• Forfait : {$tarif->nom}\n";
            $message .= "• Montant : {$montant}\n";
            $message .= "• Date de début : {$dateDebutFormatted}\n";
            $message .= "• Date de fin : {$dateFinFormatted}\n";
            $message .= "• Durée : {$tarif->duree_jours} jours\n\n";

            if ($tarif->description) {
                $message .= "Description : {$tarif->description}\n\n";
            }

            $message .= "Votre agence est maintenant active sur Assuria Pro.\n";
            $message .= "Connectez-vous pour gérer vos opérations.\n\n";
            $message .= "Merci de votre confiance !\n";
            $message .= "L'équipe Assuria Pro";

            // Envoi du SMS via l'API AfricSMS
            $response = Http::asMultipart()->post(
                'https://api.afriksms.com/api/web/web_v1/outbounds/send_multisms',
                [
                    ['name' => 'ApiKey', 'contents' => config('services.afriksms.api_key')],
                    ['name' => 'ClientId', 'contents' => config('services.afriksms.client_id')],
                    ['name' => 'SenderId', 'contents' => config('services.afriksms.sender_id')],
                    ['name' => 'Message', 'contents' => $message],
                    ['name' => 'MobileNumbers', 'contents' => '228' . $telephoneClean],
                ]
            );

            if ($response->successful()) {
                Log::info('SMS de confirmation de souscription envoyé avec succès', [
                    'agence_id' => $agence->id,
                    'telephone' => $telephoneClean,
                    'tarif' => $tarif->nom
                ]);
            } else {
                Log::error('Échec de l\'envoi du SMS de confirmation', [
                    'agence_id' => $agence->id,
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi du SMS de confirmation : ' . $e->getMessage(), [
                'agence_id' => $agence->id ?? null
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * Afficher les détails d'une souscription (format JSON pour AJAX)
     */
    public function show(string $id)
    {
        try {
            $soubscription = Soubscription::with(['agence', 'tarif'])->findOrFail($id);

            return response()->json([
                'id'             => $soubscription->id,
                'agence_nom'     => $soubscription->agence->nom ?? '—',
                'agence_email'   => $soubscription->agence->email ?? '—',
                'agence_tel'     => $soubscription->agence->telephone ?? '—',
                'tarif_nom'      => $soubscription->tarif->nom ?? '—',
                'tarif_prix'     => number_format($soubscription->tarif->prix ?? 0, 0, ',', ' ') . ' FCFA',
                'duree_jours'    => $soubscription->tarif->duree_jours ?? 0,
                'date_debut'     => $soubscription->date_debut ? $soubscription->date_debut->format('d/m/Y') : '—',
                'date_fin'       => $soubscription->date_fin ? $soubscription->date_fin->format('d/m/Y') : '—',
                'status'         => (bool) $soubscription->status,
                'status_label'   => $soubscription->status_label,
                'created_at'     => $soubscription->created_at ? $soubscription->created_at->format('d/m/Y H:i') : '—',
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Souscription introuvable.'], 404);
        }
    }

    /**
     * Désactiver une souscription active
     */
    public function desactiver(string $id)
    {
        try {
            $soubscription = Soubscription::findOrFail($id);

            if (!$soubscription->status) {
                return redirect()->back()->with('error', 'Cette souscription est déjà inactive.');
            }

            // Passation du statut à inactif (0 / false)
            $soubscription->update([
                'status' => false,
            ]);

            return redirect()->back()->with('success', 'La souscription a été désactivée avec succès.');
        } catch (\Throwable $e) {
            Log::error('Erreur lors de la désactivation de la souscription : ' . $e->getMessage());

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la désactivation.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
