<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'fec' => $this->fec ? 
                Carbon::parse($this->fec)->setTimezone('America/Lima')->format('Y-m-d') : null,
            'hora_ini' => $this->hora_ini ? 
                Carbon::parse($this->hora_ini)->format('H:i:s') : null,
            'hora_fin' => $this->hora_fin ? 
                Carbon::parse($this->hora_fin)->format('H:i:s') : null,
            'game_id' => $this->game_id,
            'participantes' => $this->whenLoaded('participantes', function () {
                return ParticipanteResource::collection($this->participantes);
            }),
        ];
    }
}
