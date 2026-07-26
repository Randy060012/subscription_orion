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
        'agence_code',
        'api_key',
        'url',
        'name',
        'email',
        'phone',
        'city',
        'agence_manager',
        'address',
        'status'
    ];

    public function soubscriptions()
    {
        return $this->hasMany(Soubscription::class, 'agence_id');
    }
}
