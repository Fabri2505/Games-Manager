export interface PlayerData {
  nombre: string;
  count: number;
}

export interface WinPlayers {
  [playerId: string]: PlayerData;
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

export interface GameResponse {
    id: number;
    name: string;
    monto: number;
    fec_juego: string;
    fec_cierre: string;
    user_id: number;
    pausado: number;
    serie_id: number;
}

export interface GameCreated {
    id_juego: number;
    name: string;
    monto: string;
    fecha_juego: string;
}