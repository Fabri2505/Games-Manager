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
}
