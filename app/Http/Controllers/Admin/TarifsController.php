<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Throwable;

class TarifsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Tarif::latest()->get();
        return view('pages.tarifs.index', compact('plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des champs
        $request->validate([
            'nom'                   => 'required|string|max:255',
            'frequence_facturation' => 'required|in:mensuel,trimestriel,annuel',
            'prix'                  => 'required|numeric|min:0',
            'duree_jours'           => 'nullable|integer|min:0',
            'max_agences'           => 'required|integer|min:0',
            'max_utilisateurs'      => 'required|integer|min:0',
            'description'           => 'nullable|string|max:1000',
            'est_actif'             => 'nullable|boolean',
        ]);

        try {
            Tarif::create([
                'nom'                   => $request->nom,
                'frequence_facturation' => $request->frequence_facturation,
                'prix'                  => $request->prix,
                'duree_jours'           => $request->duree_jours ?? 0,
                'max_agences'           => $request->max_agences,
                'max_utilisateurs'      => $request->max_utilisateurs,
                'description'           => $request->description,
                'est_actif'             => $request->has('est_actif') ? 1 : 0,
            ]);

            return redirect()->back()
                ->with('success', 'Plan d\'abonnement créé avec succès.');
        } catch (Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $tarif = Tarif::findOrFail($id);
        return response()->json($tarif);
    }

    /**
     * Met à jour le tarif spécifié
     */
    public function update(Request $request, string $id)
    {
        $tarif = Tarif::findOrFail($id);

        $request->validate([
            'nom'                   => 'required|string|max:255',
            'frequence_facturation' => 'required|in:mensuel,trimestriel,annuel',
            'prix'                  => 'required|numeric|min:0',
            'duree_jours'           => 'nullable|integer|min:0',
            'max_agences'           => 'required|integer|min:0',
            'max_utilisateurs'      => 'required|integer|min:0',
            'description'           => 'nullable|string|max:1000',
            'est_actif'             => 'nullable|boolean',
        ]);

        try {
            $tarif->update([
                'nom'                   => $request->nom,
                'frequence_facturation' => $request->frequence_facturation,
                'prix'                  => $request->prix,
                'duree_jours'           => $request->duree_jours ?? 0,
                'max_agences'           => $request->max_agences,
                'max_utilisateurs'      => $request->max_utilisateurs,
                'description'           => $request->description,
                'est_actif'             => $request->has('est_actif') ? 1 : 0,
            ]);

            return redirect()->back()->with('success', 'Plan d\'abonnement mis à jour avec succès.');
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    /**
     * Supprime le tarif
     */
    public function destroy(string $id)
    {
        try {
            $tarif = Tarif::findOrFail($id);
            $tarif->delete();

            return redirect()->back()->with('success', 'Plan d\'abonnement supprimé avec succès.');
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'Impossible de supprimer ce plan : ' . $e->getMessage());
        }
    }
}
