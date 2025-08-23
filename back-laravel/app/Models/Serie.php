<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = [
        'id',
        'name',
        'user_id',
        'is_active'
    ];

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
