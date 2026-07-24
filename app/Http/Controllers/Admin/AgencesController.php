<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        // 1. Validation des champs
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:agences,email',
            'phone' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:255',
            'agence_manager' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        try {

            // Génération du code : 'AGE' + '-' + 12 caractères aléatoires majuscules = 16 caractères au total
            $agenceCode = 'AGE-' . strtoupper(Str::random(12));

            // 2. Création directe de l'agence
            Agence::create([
                'agence_code' => $agenceCode,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'city' => $request->city,
                'agence_manager' => $request->agence_manager,
                'address' => $request->address,
                'status' => $request->has('status') ? $request->status : 1,
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
