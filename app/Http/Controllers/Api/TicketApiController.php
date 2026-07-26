<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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

            return response()->json([
                'success' => true,
                'message' => 'Ticket de support créé avec succès.',
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
}
