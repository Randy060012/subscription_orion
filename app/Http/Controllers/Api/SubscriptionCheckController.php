<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Soubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubscriptionCheckController extends Controller
{
    //
    public function checkStatus(Request $request)
    {
        // Récupération de l'agence injectée par le middleware d'authentification
        $agence = $request->get('authenticated_agence');

        if (!$agence) {
            return response()->json([
                'success' => false,
                'status' => 'unauthenticated',
                'is_active' => false,
                'message' => 'Agence introuvable ou non authentifiée.',
                'data' => null
            ], 401);
        }

        // Recherche de la dernière souscription de l'agence
        $subscription = Soubscription::with('tarif')
            ->where('agence_id', $agence->id)
            ->latest('id')
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => true,
                'status' => 'no_subscription',
                'is_active' => false,
                'message' => 'Aucune souscription trouvée pour cette agence.',
                'data' => null
            ], 200);
        }

        // Calcul de l'expiration en incluant toute la journée de fin
        $dateFin = $subscription->date_fin ? $subscription->date_fin->clone()->endOfDay() : null;
        $isExpired = $dateFin ? $dateFin->isPast() : false;

        // Une souscription est active si le statut est valide ET que la date de fin n'est pas dépassée
        $isActive = (bool) $subscription->status && !$isExpired;

        // Calcul du nombre de jours restants jusqu'à la fin de la journée d'expiration
        $daysRemaining = 0;
        if ($isActive && $dateFin) {
            $daysRemaining = (int) max(0, ceil(now()->diffInDays($dateFin, false)));
        }

        return response()->json([
            'success' => true,
            'status' => $isActive ? 'active' : ($isExpired ? 'expired' : 'inactive'),
            'is_active' => $isActive,
            'message' => $isActive ? 'Souscription valide.' : 'Souscription expirée ou inactive.',
            'data' => [
                'subscription_id' => $subscription->id,
                'plan_name' => $subscription->tarif->nom ?? 'Plan Standard',
                'date_debut' => $subscription->date_debut ? $subscription->date_debut->format('Y-m-d H:i:s') : null,
                'date_fin' => $subscription->date_fin ? $subscription->date_fin->format('Y-m-d H:i:s') : null,
                'days_remaining' => $daysRemaining,
            ]
        ], 200);
    }

    public function verifyAgency(Request $request)
    {
        // 1. Validation des données envoyées par l'instance cliente
        $validator = Validator::make($request->all(), [
            'code_agence'          => 'required|string',
            'nom_entreprise'       => 'nullable|string|max:255',
            'email_entreprise'     => 'nullable|email|max:255',
            'telephone_entreprise' => 'nullable|string|max:50',
            'agence_url'               => 'nullable|string|max:255',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status'  => 'validation_error',
                'message' => 'Les données transmises sont invalides.',
                'data'    => $validator->errors()
            ], 422);
        }

        $codeAgence = $request->input('code_agence');

        // 2. Recherche de l'agence dans la BDD centrale
        $agence = Agence::where('code_agence', $codeAgence)->first();

        // Si le code agence n'existe pas en BDD
        if (!$agence) {
            return response()->json([
                'success' => false,
                'status'  => 'agency_not_found',
                'message' => 'Code agence introuvable ou invalide.',
                'data'    => null
            ], 404);
        }

        // 3. Vérification si l'agence est bloquée ou désactivée côté central
        if (isset($agence->is_active) && !$agence->is_active) {
            return response()->json([
                'success' => false,
                'status'  => 'agency_blocked',
                'message' => 'Cette agence est désactivée. Veuillez contacter le support.',
                'data'    => null
            ], 403);
        }

        // 4. Génération de la clé API si elle n'existe pas encore
        if (empty($agence->cle_api)) {
            $agence->cle_api = Str::random(40);
        }

        // Met à jour les infos de l'instance si fournies lors de l'onboarding
        if ($request->filled('agence_url')) {
            $agence->url = $request->input('agence_url');
        }

        $agence->save();

        // 5. Réponse de succès avec les identifiants pour l'instance locale
        return response()->json([
            'success' => true,
            'status'  => 'verified',
            'message' => 'Code agence vérifié avec succès.',
            'data'    => [
                'code_agence' => $agence->code_agence,
                'cle_api'     => $agence->cle_api, 
                'check_url'   => $agence->url,
            ]
        ], 200);
    }
}
