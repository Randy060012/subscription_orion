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
        'name',
        'email',
        'phone',
        'city',
        'agence_manager',
        'address',
        'status'
    ];
}
