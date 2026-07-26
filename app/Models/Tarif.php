<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tarif extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'nom',
        'frequence_facturation',
        'prix',
        'duree_jours',
        'max_agences',
        'max_utilisateurs',
        'description',
        'est_actif',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'duree_jours' => 'integer',
        'max_agences' => 'integer',
        'max_utilisateurs' => 'integer',
        'est_actif' => 'boolean',
    ];
}
