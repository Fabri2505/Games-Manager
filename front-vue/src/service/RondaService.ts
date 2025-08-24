import type { RondaApiResponse, RondaCreateRequest, RondaData } from "@/utils/schema";

export class RondaService{
    private baseUrl = "http://localhost:8000/api/rondas";

    async createRonda(params:RondaCreateRequest):Promise<RondaData>{
        const response = await fetch(`${this.baseUrl}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(params)
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const apiResponse: RondaApiResponse = await response.json();
        
        if (!apiResponse.success) {
            throw new Error(apiResponse.message || 'Error al crear la ronda');
        }

        return apiResponse.data;

    }

}

export const rondaService = new RondaService();