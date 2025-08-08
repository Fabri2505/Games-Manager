<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'ape',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Crear token con habilidades específicas
     */
    public function createAuthToken($name = 'auth-token', array $abilities = ['*'])
    {
        return $this->createToken($name, $abilities);
    }

    /**
     * Revocar todos los tokens del usuario
     */
    public function revokeAllTokens()
    {
        return $this->tokens()->delete();
    }

    /**
     * Revocar token actual
     */
    public function revokeCurrentToken()
    {
        $token = $this->currentAccessToken();
        return $token ? $token->delete() : false;
    }
}
