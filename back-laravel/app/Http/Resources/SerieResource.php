<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SerieResource extends JsonResource
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
            'user_id' => $this->user_id,
            'is_active' => $this->is_active,
            'games_count' => $this->when(
                isset($this->games_count), 
                $this->games_count
            ),
            "pagado" => $this->pagado,
            'created_at' => $this->created_at?
                Carbon::parse($this->created_at)->setTimezone('America/Lima')->format('Y-m-d H:i:s'):null,
        ];
    }
}
