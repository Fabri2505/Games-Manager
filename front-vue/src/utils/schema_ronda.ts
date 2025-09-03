import type { User } from "./schema";

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