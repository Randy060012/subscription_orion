<?php

namespace App\Http\Middleware;

use App\Models\Agence;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAgenceHeaderForTicket
{
  public function handle(Request $request, Closure $next): Response
    {
        // 1. Récupération du header personnalisé
        $codeAgenceHeader = $request->header('X-Agence-Code');

        // 2. Récupération de agence_id (recherche dans le body POST, puis dans l'URL GET)
        $agenceId = $request->input('agence_id') ?? $request->query('agence_id');

        // 3. Vérification de la présence du Header
        if (!$codeAgenceHeader) {
            return response()->json([
                'success' => false,
                'message' => 'En-tête de sécurité manquant. Le header "X-Agence-Code" est obligatoire.'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Si tu veux permettre la recherche uniquement par code_agence lorsque agence_id n'est pas fourni :
        $query = Agence::where('code_agence', $codeAgenceHeader);

        if ($agenceId) {
            $query->where('id', $agenceId);
        }

        $agence = $query->first();

        if (!$agence) {
            return response()->json([
                'success' => false,
                'message' => 'Authentification de l\'agence échouée. L\'identifiant ou le code agence est invalide.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Injection de l'agence authentifiée dans la requête
        $request->attributes->set('authenticated_agence', $agence);

        return $next($request);
    }
}
