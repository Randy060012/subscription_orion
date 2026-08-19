<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use Throwable;

class AgencesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agences = Agence::latest()->get();
        return view('pages.agences.index', compact('agences'));
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
        // 1. Validation des champs (noms en français)
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:agences,email',
            'telephone' => 'nullable|string|max:50',
            'ville' => 'nullable|string|max:255',
            'responsable' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
            'statut' => 'nullable|boolean',
        ]);

        try {

            // Génération du code : 'AGE' + '-' + 12 caractères aléatoires majuscules = 16 caractères au total
            $agenceCode = 'AGE-' . strtoupper(Str::random(12));

            // Génération de la clé API de 16 caractères
            $apiKey = \Illuminate\Support\Str::random(16);

            // 2. Création directe de l'agence
            Agence::create([
                'code_agence' => $agenceCode,
                'cle_api' => $apiKey,
                'url' => $request->url,
                'nom' => $request->nom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'ville' => $request->ville,
                'responsable' => $request->responsable,
                'adresse' => $request->adresse,
                'statut' => $request->has('statut') ? $request->statut : 1,
            ]);

            return redirect()->back()
                ->with('success', 'Agence créée avec succès.');
        } catch (Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $agence = Agence::withCount(['soubscriptions', 'tickets'])->findOrFail($id);

        return response()->json($agence);
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
        $agence = Agence::findOrFail($id);

        // Validation avec gestion de l'unicité de l'email pour l'agence courante
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('agences', 'email')->ignore($agence->id)],
            'telephone' => 'nullable|string|max:50',
            'ville' => 'nullable|string|max:255',
            'responsable' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
            'statut' => 'required|boolean',
        ]);

        try {
            $agence->update([
                'nom' => $request->nom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'ville' => $request->ville,
                'responsable' => $request->responsable,
                'adresse' => $request->adresse,
                'url' => $request->url,
                'statut' => $request->statut,
            ]);

            return redirect()->back()->with('success', 'Agence mise à jour avec succès.');
        } catch (Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $agence = Agence::findOrFail($id);
            $agence->delete();

            return redirect()->back()->with('success', 'Agence supprimée avec succès.');
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'Impossible de supprimer cette agence : ' . $e->getMessage());
        }
    }
}
