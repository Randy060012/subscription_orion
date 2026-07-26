<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // À ajuster selon ta logique d'authentification API
    }

    public function rules(): array
    {
        return [
            'agence_id'     => 'required|integer|exists:agences,id',
            'type_demande'  => 'required|string|in:bug,amelioration,nouveau_module,formation',
            'nom_demandeur' => 'required|string|max:255',
            'priorite'      => 'required|string|in:basse,moyenne,haute,urgente',
            'sujet'         => 'required|string|max:255',
            'description'   => 'required|string',
            'piece_jointe'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // Max 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'agence_id.required'    => 'L\'agence concernée est obligatoire.',
            'agence_id.exists'      => 'L\'agence sélectionnée n\'existe pas.',
            'type_demande.in'       => 'Le type de demande est invalide.',
            'priorite.in'           => 'Le niveau de priorité est invalide.',
            'piece_jointe.max'      => 'La pièce jointe ne doit pas dépasser 5 Mo.',
        ];
    }
}
