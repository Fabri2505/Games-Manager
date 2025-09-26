import type { Player, User } from "@/utils/schema_participante";

export class PlayerService{
    private baseUrl = "http://localhost:8000/api/players";

    async getPlayers():Promise<Player[]>{
        try{
            const response = await fetch(`${this.baseUrl}`);

            if(!response.ok){
                throw new Error(`HTTP ${response.status}: ${response.statusText}`)
            }

            const data = await response.json();
            const players = data.players || data;

            // Validar que realmente tenemos un array
            if (!Array.isArray(players)) {
                throw new Error('La respuesta de la API no contiene un array válido de jugadores');
            }

            // ✅ Mapear los datos de la API a tu interface
            return players.map((player: User) => ({
                id: player.id,
                email: player.email,
                name: `${player.name} ${player.ape}`
            }));
        }catch(error){
            console.error("Error fetching players:", error);
            throw error; // Re-lanzar el error para que pueda ser manejado por el llamador
        }
    }

}

export const playerService = new PlayerService();