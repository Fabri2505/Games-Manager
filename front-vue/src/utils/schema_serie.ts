export interface SerieResponse {
   id: number;
   name: string;
   user_id: number;
   is_active: number;
   created_at: string;
   updated_at: string;
   games_count: number;
}

export interface Serie {
    id: number;
    name: string;
    created: string;
    games: number;
}