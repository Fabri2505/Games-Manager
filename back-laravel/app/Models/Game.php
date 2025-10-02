<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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


    public function getAnalityPlayers()
    {
        $rondas = $this->rondas()
        ->withCount('participantes')
        ->with(['participantes' => function ($query) {
            $query->select('id', 'user_id', 'ronda_id', 'winner');
        }, 'participantes.user:id,name,ape'])
        ->get();

        if ($rondas->isEmpty()) {
            return null;
        }

        $players = [];
        $rondasSinGanador = 0;

        foreach ($rondas as $ronda) {
            $ronda->participantes->map(function($p) {
                Log::info('Participante en ronda ' . $p->ronda_id . ': ' . $p->id . ' (Winner: ' . ($p->winner ? 'Sí' : 'No') . ')');
            });
            // ✅ Verificar si algún participante es ganador
            $tieneGanador = $ronda->participantes->contains('winner', true);

            if (!$tieneGanador) {
                $rondasSinGanador++;
                Log::info('Ronda ' . $ronda->id . ' no tiene ganador');
                continue; // Saltar esta ronda
            }

            foreach ($ronda->participantes as $participante) {
                $userId = $participante->user_id;

                if (!isset($players[$userId])) {
                    $players[$userId] = [
                        'user_id' => $userId,
                        'user' => $participante->user->name.' '.$participante->user->ape,
                        'monto' => 0,
                        'rondas_ganadas' => 0
                    ];
                }

                Log::info('Procesando participante: ' . $participante->id . ' en ronda: ' . $ronda->id);

                if ($participante->winner) {
                    $monto_ganado_ronda = ($ronda->participantes_count - 1)*$this->monto;
                    $players[$userId]['monto'] += $monto_ganado_ronda; // Suma el monto ganado
                    $players[$userId]['rondas_ganadas']++;
                }else{
                    $players[$userId]['monto'] -= $this->monto; // Resta el monto por participar
                }
            }
        }

        // Ordenar los jugadores por monto ganado de mayor a menor
        $playersOrdenados = collect($players)->sortByDesc('monto')->values()->all();

        return [
            'players' => $playersOrdenados,
            'rondas_sin_ganador' => $rondasSinGanador // ✅ Retornar la cantidad
        ];
    }

}
