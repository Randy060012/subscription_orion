<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Agence;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TicketApiController extends Controller
{
    //

    public function index(Request $request)
    {
        // On récupère l'agence injectée dans le middleware
        $agence = $request->attributes->get('authenticated_agence');

        $tickets = $agence->tickets()
            ->when($request->has('statut'), function ($query) use ($request) {
                return $query->where('statut', $request->query('statut'));
            })
            ->latest()
            ->paginate($request->query('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Liste des tickets récupérée avec succès.',
            'data'    => TicketResource::collection($tickets),
            'meta'    => [
                'current_page' => $tickets->currentPage(),
                'last_page'    => $tickets->lastPage(),
                'per_page'     => $tickets->perPage(),
                'total'        => $tickets->total(),
            ]
        ], Response::HTTP_OK);
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['code'] = Ticket::generateUniqueCode();

            if ($request->hasFile('piece_jointe')) {
                $path = $request->file('piece_jointe')->store('tickets/attachments', 'public');
                $data['piece_jointe'] = $path;
            }

            $ticket = Ticket::create($data);

            DB::commit();

            // =========================================================
            // ENVOI DES NOTIFICATIONS SMS
            // =========================================================

            // 2. SMS À L'AGENCE (Récupéré depuis la table agences via agence_id)
            $agence = Agence::find($ticket->agence_id);

            // Ajustez 'telephone' selon le nom exact du champ dans votre table agences (ex: telephone, telephone_agence, contact)
            $telephoneAgence = $agence?->telephone ?? $agence?->telephone_agence;

            if (!empty($telephoneAgence)) {
                $msgAgence = "NOUVEAU TICKET #{$ticket->code} ! Sujet: {$ticket->sujet} | Priorité: {$ticket->priorite} | De: {$ticket->nom_demandeur}.";
                $this->envoyerSmsAfrikSms($telephoneAgence, $msgAgence);
            }

            // 3. (Optionnel) SMS AUX ADMINISTRATEURS / SUPPORT TECHNIQUE
            $adminPhonesConfig = config('services.afriksms.admin_phone', '');
            $adminPhones = array_filter(array_map('trim', explode(',', $adminPhonesConfig)));

            if (!empty($adminPhones)) {
                $msgAdmin = "ALERTE TICKET #{$ticket->code} pour l'agence " . ($agence?->nom ?? 'Inconnue') . " - Sujet: {$ticket->sujet}.";
                foreach ($adminPhones as $phone) {
                    $this->envoyerSmsAfrikSms($phone, $msgAdmin);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket de support créé avec succès et notifications envoyées.',
                'data'    => $ticket
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création du ticket.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }



    private function envoyerSmsAfrikSms(string $telephone, string $message): void
    {
        try {
            // Nettoyage du numéro de téléphone
            $telephoneClean = preg_replace('/[^0-9]/', '', $telephone);

            // Si le numéro commence déjà par 228, on évite le doublon
            if (str_starts_with($telephoneClean, '228')) {
                $telephoneClean = substr($telephoneClean, 3);
            }

            Http::asMultipart()->post(
                'https://api.afriksms.com/api/web/web_v1/outbounds/send_multisms',
                [
                    ['name' => 'ApiKey', 'contents' => config('services.afriksms.api_key')],
                    ['name' => 'ClientId', 'contents' => config('services.afriksms.client_id')],
                    ['name' => 'SenderId', 'contents' => config('services.afriksms.sender_id')],
                    ['name' => 'Message', 'contents' => $message],
                    ['name' => 'MobileNumbers', 'contents' => '228' . $telephoneClean],
                ]
            );
        } catch (\Throwable $e) {
            Log::error("Échec d'envoi SMS à {$telephone} : " . $e->getMessage());
        }
    }
}
