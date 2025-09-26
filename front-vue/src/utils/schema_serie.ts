export interface SerieResponse {
   id: number;
   name: string;
   user_id: number;
   is_active: number;
   games_count: number;
   pagado: number;
   created_at: string;
}

export interface Serie {
    id: number;
    name: string;
    created: string;
    games: number;
}