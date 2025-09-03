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

        return [
            'user' => $ultimoGanador->user,
            'user_id' => $userId,
            'longitud' => $rachaCount,
            'rondas_ganadas' => array_reverse($rondas_ganadas)
        ];
    }

}
