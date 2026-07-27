<?php

namespace App\Http\Middleware;

use App\Models\Agence;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAgenceApi
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Récupération des paramètres (soit dans les Headers, soit dans le Body JSON/Query)
        //$apiKey = $request->header('X-API-KEY') ?? $request->input('cle_api');
        $agenceCode = $request->header('X-AGENCE-CODE') ?? $request->input('code_agence');
        $agenceUrl = $request->header('X-AGENCE-URL') ?? $request->input('agence_url');

        // Validation de la présence des champs requis
        if (/*!$apiKey ||*/ !$agenceCode || !$agenceUrl) {
            return response()->json([
                'success' => false,
                'message' => 'Paramètres d\'authentification manquants (cle_api, code_agence, agence_url requis).'
            ], 401);
        }

        // 2. Normalisation de l'URL pour la comparaison (ex: ignorer trailing slashes et http/https si besoin)
        $cleanUrl = parse_url($agenceUrl, PHP_URL_HOST) ?? $agenceUrl;

        // 3. Recherche de l'agence correspondante
        $agence = Agence::where('code_agence', $agenceCode)
            //->where('cle_api', $apiKey)
            ->get()
            ->filter(function ($a) use ($cleanUrl) {
                // On vérifie si l'URL renseignée correspond à l'agence enregistrée
                $dbHost = parse_url($a->url, PHP_URL_HOST) ?? $a->url;
                return strtolower($dbHost) === strtolower($cleanUrl);
            })
            ->first();

        if (!$agence) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants ou URL d\'agence invalides.'
            ], 403);
        }

        // 4. Injecter l'agence authentifiée dans la requête pour le Controller
        $request->merge(['authenticated_agence' => $agence]);

        return $next($request);
    }
}
