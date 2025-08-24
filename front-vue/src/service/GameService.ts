import type { GameCreated } from "@/utils/schema";

export class GameService{
    private baseUrl = "http://localhost:8000/api/game";

    async createGame(
        params:{name_serie:string, name_game:string, monto:number, user_id:number}
    ):Promise<GameCreated>{
        console.log('Creando juego con parámetros:', params);
        const response = await fetch(`${this.baseUrl}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(params)
        });

        if(!response.ok){
            throw new Error(`HTTP ${response.status}: ${response.statusText}`)
        }

        const data = await response.json();
        const juegoCreado = data.game || data;

        return {
            id_juego: juegoCreado.id,
            descripcion: juegoCreado.descrip,
            monto: juegoCreado.monto,
            fecha_juego: juegoCreado.fec_juego
        }

    }

    // Aquí puedes agregar métodos relacionados con los juegos
}

export const gameService = new GameService();