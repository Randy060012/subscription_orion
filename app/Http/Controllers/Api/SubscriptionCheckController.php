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
        // Récupération de l'agence injectée par le middleware
        $agence = $request->get('authenticated_agence');

        // Recherche de la dernière souscription active de l'agence
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

        // Détermination du statut réel (date de fin vs statut colonne)
        $isExpired = $subscription->date_fin && $subscription->date_fin->isPast();
        $isActive = $subscription->status && !$isExpired;

        return response()->json([
            'success' => true,
            'status' => $isActive ? 'active' : ($isExpired ? 'expired' : 'inactive'),
            'is_active' => (bool) $isActive,
            'message' => $isActive ? 'Souscription valide.' : 'Souscription expirée ou inactive.',
            'data' => [
                'subscription_id' => $subscription->id,
                'plan_name' => $subscription->tarif->name ?? 'Non défini',
                'date_debut' => $subscription->date_debut ? $subscription->date_debut->format('Y-m-d H:i:s') : null,
                'date_fin' => $subscription->date_fin ? $subscription->date_fin->format('Y-m-d H:i:s') : null,
                'days_remaining' => ($isActive && $subscription->date_fin)
                    ? now()->diffInDays($subscription->date_fin, false)
                    : 0,
            ]
        ], 200);
    }
}
