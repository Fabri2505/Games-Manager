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
    monto: number;
    fecha_juego: string;
}