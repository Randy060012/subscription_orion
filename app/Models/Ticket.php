<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Ticket extends Model
{
    //
    use HasFactory;
    protected $fillable =
    [
        'code',
        'agence_id',
        'type_demande',
        'nom_demandeur',
        'priorite',
        'sujet',
        'description',
        'piece_jointe',
        'statut'
    ];

    public static function generateUniqueCode(): string
    {
        do {
            $code = 'TCK-' . date('Y') . '-' . strtoupper(Str::random(5));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function agence()
    {
        return $this->belongsTo(Agence::class, 'agence_id');
    }
}
