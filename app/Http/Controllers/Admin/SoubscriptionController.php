<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Soubscription;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SoubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $soubscriptions = Soubscription::with(['agence', 'tarif'])->latest()->get();

        $agences = Agence::where('statut', 1)->get();
        $tarifs = Tarif::where('est_actif', 1)->get();

        // Calculs KPI
        $totalActifs = $soubscriptions->where('status', 1)->count();

        $expired = $soubscriptions->filter(function ($s) {
            return $s->isExpired();
        })->count();

        $expiringSoon = $soubscriptions->filter(function ($s) {
            return $s->isExpiringSoon();
        })->count();

        $revenuTotal = $soubscriptions->where('status', 1)->sum(function ($s) {
            return $s->tarif ? $s->tarif->prix : 0;
        });

        return view('pages.soubscriptions.index', compact(
            'agences',
            'tarifs',
            'soubscriptions',
            'totalActifs',
            'expired',
            'expiringSoon',
            'revenuTotal'
        ));
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
        $request->validate([
            'agence_id' => 'required|exists:agences,id',
            'tarif_id'  => 'required|exists:tarifs,id',
            // 'date_debut' => 'required|date',
            'status'    => 'nullable|boolean',
        ]);

        try {
            $tarif = Tarif::findOrFail($request->tarif_id);

            $dateDebut = Carbon::now();
            $dateFin   = $dateDebut->copy()->addDays((int) $tarif->duree_jours);

            Soubscription::create([
                'agence_id'  => $request->agence_id,
                'tarif_id'   => $request->tarif_id,
                'date_debut' => $dateDebut->format('Y-m-d'),
                'date_fin'   => $dateFin->format('Y-m-d'),
                'status'     => $request->has('status') ? $request->status : 1,
            ]);

            return redirect()->back()->with('success', 'Soubscription créé avec succès.');
        } catch (\Throwable $e) {
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
