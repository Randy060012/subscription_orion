<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code ?? $this->reference,
            'sujet' => $this->sujet,
            'description' => $this->description,
            'statut' => $this->statut, // ex: 'ouvert', 'en_cours', 'ferme'
            'priorite' => $this->priorite, // ex: 'basse', 'moyenne', 'haute'
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
