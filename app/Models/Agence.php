<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
    //
    use HasFactory;
    protected $fillable =
    [
        'code_agence',
        'cle_api',
        'url',
        'nom',
        'email',
        'telephone',
        'ville',
        'responsable',
        'adresse',
        'statut'
    ];

    public function soubscriptions()
    {
        return $this->hasMany(Soubscription::class, 'agence_id');
    }
}
