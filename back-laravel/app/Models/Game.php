<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'monto',
        'fec_juego',
        'fec_cierre',
        'user_id',
        'pausado',
        'serie_id'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fec_juego' => 'datetime',
        'fec_cierre' => 'datetime'
    ];

    public function rondas()
    {
        return $this->hasMany(Ronda::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }

    public function calcularJugadorEnRacha()
    {
        $rondas = $this->rondas()
            ->orderBy('fec', 'desc')
            ->orderBy('hora_ini', 'desc')
            ->with('participantes.user')
            ->get();

        if ($rondas->isEmpty()) {
            return null;
        }

        // Encontrar al último ganador disponible
        $ultimoGanador = null;

        foreach ($rondas as $ronda) {
            $ganador = $ronda->participantes->where('winner', true)->first();
            if ($ganador) {
                $ultimoGanador = $ganador;
                break; // Encontramos al último ganador
            }
        }

        if (!$ultimoGanador) {
            return null; // No hay ganadores en ninguna ronda
        }

        $userId = $ultimoGanador->user_id;
        $rachaCount = 0;
        $rondas_ganadas = [];

        // Ahora contar hacia atrás desde todas las rondas
        foreach ($rondas as $ronda) {
            $ganador = $ronda->participantes->where('winner', true)->first();
            
            if ($ganador && $ganador->user_id === $userId) {
                $rachaCount++;
                $rondas_ganadas[] = $ronda->id;
            } else {
                break; // Se rompió la racha
            }
        }

        if ($rachaCount > 2){
            return [
                'user_name' => $ultimoGanador->user->name .' '.$ultimoGanador->user->ape,
                'user_id' => $userId,
                'longitud' => $rachaCount
            ];
        }else{
            return null;
        }

    }

    public function getLiderGame()
    {
        $rondas = $this->rondas()
        ->whereHas('participantes', function ($query) {
            $query->where('winner', true);
        })
        ->withCount('participantes')
        ->with(['participantes' => function ($query) {
            $query->where('winner', true)
                ->select('id', 'user_id', 'ronda_id', 'winner');
        }, 'participantes.user:id,name,ape'])
        ->get();

        if ($rondas->isEmpty()) {
            return null;
        }

        $lideres = [];

        foreach ($rondas as $ronda) {

            $participante = $ronda->participantes->first();

            $monto_ganado_ronda = ($ronda->participantes_count - 1)*$this->monto;

            if (!$participante) {
                continue; // No hay ganador en esta ronda
            }
            $userId = $participante->user_id;

            if (!isset($lideres[$userId])) {
                $lideres[$userId] = [
                    'user' => $participante->user->name.' '.$participante->user->ape,
                    'monto' => 0,
                    'rondas_ganadas' => 0
                ];
            }

            $lideres[$userId]['monto'] += $monto_ganado_ronda;
            $lideres[$userId]['rondas_ganadas']++;

        }

        $liderPrincipal = collect($lideres)->sortByDesc('rondas_ganadas')->first();
        return $liderPrincipal;
    }

}
