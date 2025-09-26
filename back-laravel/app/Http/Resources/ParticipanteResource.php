<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'winner' => $this->winner,
            'ronda_id' => $this->ronda_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at ?
                Carbon::parse($this->created_at)->setTimezone('America/Lima')->format('Y-m-d H:i:s') : null
        ];
    }
}
