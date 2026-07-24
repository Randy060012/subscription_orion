<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tarif extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'billing_frequency',
        'price',
        'trial_period_days',
        'max_agencies',
        'max_users',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'trial_period_days' => 'integer',
        'max_agencies' => 'integer',
        'max_users' => 'integer',
        'is_active' => 'boolean',
    ];
}
