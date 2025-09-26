<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipanteResource extends JsonResource
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
            'ronda_id' => $this->ronda_id,
            'user_id' => $this->user_id,
            'score' => $this->score,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
