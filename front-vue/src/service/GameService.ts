import type { GameCreated, GameResponse } from "@/utils/schema_game";
import type { AnalityRonda, LastRondaResponse } from "@/utils/schema_ronda";

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

        const game = await response.json();
        const juegoCreado:GameResponse = game.data;

        return {
            id_juego: juegoCreado.id,
            name: juegoCreado.name,
            monto: juegoCreado.monto,
            fecha_juego: juegoCreado.fec_juego
        }

    }

    async getLastRonda(id_game:number):Promise<LastRondaResponse>{
        const response = await fetch(`${this.baseUrl}/${id_game}/last-ronda`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if(!response.ok){
            throw new Error(`HTTP ${response.status}: ${response.statusText}`)
        }

        const data: LastRondaResponse = await response.json();
        return data;
    }

    async getAnalitycs(id_game:number):Promise<AnalityRonda>{
        const response = await fetch(`${this.baseUrl}/${id_game}/anality`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if(!response.ok){
            throw new Error(`HTTP ${response.status}: ${response.statusText}`)
        }

        const data: AnalityRonda = await response.json();
        return data;
    }
}

export const gameService = new GameService();