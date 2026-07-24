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
            'name'              => 'required|string|max:255',
            'billing_frequency' => 'required|in:mensuel,trimestriel,annuel',
            'price'             => 'required|numeric|min:0',
            'trial_period_days' => 'nullable|integer|min:0',
            'max_agencies'      => 'required|integer|min:0',
            'max_users'         => 'required|integer|min:0',
            'description'       => 'nullable|string|max:1000',
            'is_active'         => 'nullable|boolean',
        ]);

        try {
            Tarif::create([
                'name'              => $request->name,
                'billing_frequency' => $request->billing_frequency,
                'price'             => $request->price,
                'trial_period_days' => $request->trial_period_days ?? 0,
                'max_agencies'      => $request->max_agencies,
                'max_users'         => $request->max_users,
                'description'       => $request->description,
                'is_active'         => $request->has('is_active') ? 1 : 0,
            ]);

            return redirect()->back()
                ->with('success', 'Plan d\'abonnement créé avec succès.');
        } catch (Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}
