<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Soubscription;
use Illuminate\Http\Request;

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
}
