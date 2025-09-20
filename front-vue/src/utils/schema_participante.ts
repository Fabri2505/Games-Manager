export interface BasicUser{
    id:number,
    name:string,
    email:string,
}

// Interfaces para la respuesta de la API
export interface User extends BasicUser{
    ape: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Participante<T=BasicUser> {
    id: number;
    winner: boolean;
    user_id: number;
    created_at: string;
    updated_at: string;
    ronda_id: number;
    user: T;
}

export interface Player extends BasicUser{
    selected?: boolean
}

export interface PlayerResponse{
    id: number,
    name: string,
    ape: string,
    email: string,
    email_verified_at: string|null,
    created_at: string,
    updated_at: string
}