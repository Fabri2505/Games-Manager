import type { Serie, SerieResponse } from "@/utils/schema";

export class SerieService{
    private baseUrl = "http://localhost:8000/api/series";

    async getSeries(
        params:{user_id:number, is_active:boolean}
    ):Promise<Serie[]>{

        const searchParams = new URLSearchParams();

        if (params.user_id !== undefined) {
            searchParams.append('user_id', params.user_id.toString());
        }
        if (params.is_active !== undefined) {
            searchParams.append('is_active', params.is_active ? '1' : '0');
        }

        const response = await fetch(`${this.baseUrl}?${searchParams.toString()}`);

        if(!response.ok){
            throw new Error(`HTTP ${response.status}: ${response.statusText}`)
        }

        const data = await response.json();
        const series = data.series || data;

        // ✅ Mapear los datos de la API a tu interface
        return series.map((serie: SerieResponse) => ({
            id: serie.id,
            name: serie.name,
            created: serie.created_at,
            games: serie.games_count
        }));

    }

}

export const serieService = new SerieService();