import type { Player } from "@/utils/schema";

export class PlayerService{
    private baseUrl = "http://localhost:8000/api";

    async getPlayers():Promise<Player[]>{
        const response = await fetch(`${this.baseUrl}/players`);

        if(!response.ok){
            throw new Error(`HTTP ${response.status}: ${response.statusText}`)
        }

        const data = await response.json();
        const players = data.players || data;

        // ✅ Mapear los datos de la API a tu interface
        return players.map((player: any) => ({
            id: player.id,
            email: player.email,
            nombre: `${player.name} ${player.ape}`, // Combinar name y ape
            // Agregar otras propiedades que necesites
        }));

    }

}

export const playerService = new PlayerService();