export interface BasicUser{
    id:number,
    name:string,
    email:string,
}

// Interfaces para la respuesta de la API
export interface User extends BasicUser{
    ape: string;
}

export interface Participante<T=BasicUser> {
    id: number;
    winner: boolean;
    created_at: string;
    ronda_id: number;
    user: T;
}

export interface Player extends BasicUser{
    selected?: boolean
}