<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Soubscription extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'agence_id',
        'tarif_id',
        'date_debut',
        'date_fin',
        'status'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'status' => 'boolean',
    ];

    protected $appends = ['status_label'];

    /**
     * Accesseur qui retourne le libellé du statut de la souscription
     * Valeurs possibles : 'actif', 'expire', 'inactif'
     */
    public function getStatusLabelAttribute(): string
    {
        $now = Carbon::now();
        $isExpired = $this->date_fin && $this->date_fin->isPast();

        if ($this->status && !$isExpired) {
            return 'actif';
        } elseif ($isExpired) {
            return 'expire';
        }

        return 'inactif';
    }

    /**
     * Vérifie si la souscription est expirée
     */
    public function isExpired(): bool
    {
        return $this->date_fin && $this->date_fin->isPast();
    }

    /**
     * Vérifie si la souscription expire bientôt (dans les 7 jours)
     */
    public function isExpiringSoon(int $days = 7): bool
    {
        return $this->date_fin
            && $this->date_fin->isFuture()
            && $this->date_fin->diffInDays(Carbon::now()) <= $days;
    }

    public function agence()
    {
        return $this->belongsTo(Agence::class, 'agence_id');
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'tarif_id');
    }
}
