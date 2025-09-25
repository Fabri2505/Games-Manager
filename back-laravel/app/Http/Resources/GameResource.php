<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
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
            'name' => $this->name,
            'monto' => $this->monto,
            'fec_juego' => $this->fec_juego ? 
                Carbon::parse($this->fec_juego)->setTimezone('America/Lima')->format('Y-m-d H:i:s') : null,
            'fec_cierre' => $this->fec_cierre ? 
                Carbon::parse($this->fec_cierre)->setTimezone('America/Lima')->format('Y-m-d H:i:s') : null,
            'user_id' => $this->user_id,
            'pausado' => $this->pausado,
            'serie_id' => $this->serie_id,
        ];

    }
}
