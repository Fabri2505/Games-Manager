export interface Player{
    id:number,
    email:string,
    nombre:string
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

export interface SerieResponse {
   id: number;
   name: string;
   user_id: number;
   is_active: number;
   created_at: string;
   updated_at: string;
   games_count: number;
}

export interface Serie {
    id: number;
    name: string;
    created: string;
    games: number;
}

export interface GameCreateResponse {
    id: number;
    descrip: string;
    monto: string;
    fec_juego: string;
    fec_cierre: string | null;
    user_id: number;
    serie_id: number;
}

export interface GameCreated {
    id_juego: number;
    name: string;
    monto: string;
    fecha_juego: string;
}

export interface RondaCreateRequest {
    game_id: number;
    participantes: number[];
}

// Interfaces para la respuesta de la API
export interface User {
    id: number;
    name: string;
    ape: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Participante {
    id: number;
    winner: boolean;
    user_id: number;
    created_at: string;
    updated_at: string;
    ronda_id: number;
    user: User;
}

export interface RondaData {
    id: number;
    fec: string;
    hora_ini: string;
    hora_fin: string | null;
    game_id: number;
    created_at: string;
    updated_at: string;
    participantes: Participante[];
}

export interface RondaApiResponse {
    success: boolean;
    message: string;
    data: RondaData;
}

export interface LastRondaResponse extends RondaApiResponse {
    nro_ronda: number; // Número total de rondas en el juego
}