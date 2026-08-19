<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Throwable;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tickets = Ticket::with('agence')->latest()->get();

        return view('pages.tickets.index', compact('tickets'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    // public function update(Request $request, Ticket $ticket)
    // {
    //     $validated = $request->validate([
    //         'statut'   => ['required', 'string', Rule::in(['en_attente', 'en_cours', 'resolu', 'ferme'])],
    //         'priorite' => ['required', 'string', Rule::in(['basse', 'moyenne', 'haute', 'urgente'])],
    //         'reponse'  => ['nullable', 'string', 'max:2000'],
    //     ]);

    //     $ticket->update($validated);

    //     return redirect()->back()->with('success', "Le ticket #{$ticket->code} a été mis à jour avec succès.");
    // }

    /**
     * Marquer un ticket comme résolu avec un commentaire de résolution
     */
    public function resoudre(Request $request, string $id)
    {
        $request->validate([
            'commentaire' => 'required|string|max:1000',
        ], [
            'commentaire.required' => 'Veuillez saisir un commentaire expliquant la résolution du ticket.',
        ]);

        try {
            $ticket = Ticket::findOrFail($id);

            $ticket->update([
                'statut'                 => 'resolu', // ou 1 / 'ferme' selon tes conventions
                'commentaire_resolution' => $request->commentaire,
                'resolu_par'             => auth()->id(), // si tu veux enregistrer l'auteur de la résolution
                'resolved_at'            => now(),
            ]);

            return redirect()->back()->with('success', 'Le ticket a été marqué comme résolu avec succès.');
        } catch (Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la résolution du ticket : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
