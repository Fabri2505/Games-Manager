import type { GameResponse } from "./schema_game";
import type { BasicUser, User , Participante } from "./schema_participante";

export interface RondaCreateRequest {
    game_id: number;
    participantes: number[];
}

export interface RondaData {
    id: number;
    fec: string;
    hora_ini: string;
    hora_fin: string | null;
    game_id: number;
    created_at: string;
    updated_at: string;
    participantes: Participante<User>[];
}

export interface RondaResponse {
    success: boolean;
    message: string;
    data: RondaData;
}

export interface RondaApiResponse extends RondaResponse {
    game: GameResponse;
}

export interface  DataResponseWinner {
    participantes: Participante<BasicUser>;
    ronda_finalizada : boolean;
}

export interface SetWinnerResponse {
    success: boolean;
    message: string;
    data: DataResponseWinner;
}

export interface LastRondaResponse extends RondaApiResponse {
    nro_ronda: number; // Número total de rondas en el juego
}

export interface JugadorEnRacha {
   user: User;
   user_id: number;
   longitud: number;
   rondas_ganadas: number[];
}

export interface AnalityRonda {
    success: true,
    message: "Análisis del juego obtenido exitosamente",
    racha: JugadorEnRacha,
    total_rondas: number;
}