import type { RondaApiResponse, RondaCreateRequest, RondaData, SetWinnerResponse } from "@/utils/schema_ronda";

export class RondaService{
    private baseUrl = "http://localhost:8000/api/rondas";

    async createRonda(params:RondaCreateRequest):Promise<RondaData>{
        try {

            const response = await fetch(`${this.baseUrl}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(params)
            });

            // Si es error 422 (validación), maneja los errores específicos
            if (response.status === 422) {
                const errorData = await response.json();
                
                // Procesar errores de validación
                const validationErrors = this.processValidationErrors(errorData.errors);
                throw new ValidationError('Errores de validación', validationErrors, errorData);
            }

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`HTTP ${response.status}: ${response.statusText} - ${errorText}`);
            }

            const apiResponse: RondaApiResponse = await response.json();
            
            if (!apiResponse.success) {
                throw new Error(apiResponse.message || 'Error al crear la ronda');
            }

            return apiResponse.data;
        } catch (error) {
            if (error instanceof ValidationError || error instanceof Error) {
                throw error;
            }

            throw new Error('Error desconocido al crear la ronda');
        }

    }

    /**
     * Para settear al ganador de la ronda
     */
    async setWinner(rondaId: number, ganadorId: number, user_id:number): Promise<SetWinnerResponse> {
        try {
            const response = await fetch(`${this.baseUrl}/${rondaId}/set-winner`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ user_id: user_id, win: ganadorId })
            })

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`HTTP ${response.status}: ${response.statusText} - ${errorText}`);
            }
            const apiResponse: SetWinnerResponse = await response.json();
            if (!apiResponse.success) {
                throw new Error(apiResponse.message || 'Error al setear el ganador');
            }
            return apiResponse;
            
        }catch (error) {
            if (error instanceof ValidationError || error instanceof Error) {
                throw error;
            }
            throw new Error('Error desconocido al setear el ganador');
        }
    }

    /**
     * Procesa los errores de validación de Laravel y los convierte en mensajes legibles
     */
    private processValidationErrors(errors: Record<string, string[]>): ValidationErrorDetail[] {
        const processedErrors: ValidationErrorDetail[] = [];

        for (const [field, messages] of Object.entries(errors)) {
            // Detectar errores de participantes
            if (field.startsWith('participantes.')) {
                const index = field.split('.')[1];
                processedErrors.push({
                    field,
                    message: `El participante en la posición ${parseInt(index) + 1} no es válido o no existe`,
                    originalMessages: messages
                });
            } else if (field === 'game_id') {
                processedErrors.push({
                    field,
                    message: 'El juego especificado no existe',
                    originalMessages: messages
                });
            } else {
                processedErrors.push({
                    field,
                    message: messages[0] || 'Error de validación',
                    originalMessages: messages
                });
            }
        }

        return processedErrors;
    }

}

// Clase personalizada para errores de validación
export class ValidationError extends Error {
    public validationErrors: ValidationErrorDetail[];
    public originalResponse: any;

    constructor(message: string, validationErrors: ValidationErrorDetail[], originalResponse?: any) {
        super(message);
        this.name = 'ValidationError';
        this.validationErrors = validationErrors;
        this.originalResponse = originalResponse;
    }

    // Método para obtener un resumen de errores
    getErrorSummary(): string {
        return this.validationErrors.map(error => error.message).join(', ');
    }
}

// Interfaces
export interface ValidationErrorDetail {
    field: string;
    message: string;
    originalMessages: string[];
}

export const rondaService = new RondaService();