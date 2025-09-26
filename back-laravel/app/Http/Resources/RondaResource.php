<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RondaResource extends JsonResource
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
            'game_id' => $this->game_id,
            'hora_ini' => $this->hora_ini,
            'hora_fin' => $this->hora_fin,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'participantes' => $this->whenLoaded('participantes', function () {
                return ParticipanteResource::collection($this->participantes);
            }),
        ];
    }
}
