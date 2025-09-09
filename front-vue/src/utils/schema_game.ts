export interface PlayerData {
  nombre: string;
  count: number;
}

export interface WinPlayers {
  [playerId: string]: PlayerData;
}