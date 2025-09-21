import type { Player } from "@/utils/schema_participante"
import { defineStore } from "pinia"
import { computed, ref } from "vue"
import { playerService } from "../service/PlayerService"

export const usePlayerStore = defineStore('players', () =>{
  const players = ref<Player[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  // 🚀 Función que carga automáticamente si no hay datos
  const ensurePlayersLoaded = async () => {
    if (players.value.length === 0 && !loading.value) {
      await fetchPlayers()
    }
  }

  const fetchPlayers = async () => {
    try{
      loading.value = true
      error.value = null

      const fetchedPlayers = await playerService.getPlayers();

      players.value = fetchedPlayers;

    }catch (err) {
      console.error("Error fetching players:", err)
      error.value = "Error al cargar los jugadores"
    }finally{
      loading.value = false
    }
  }

  const totalPlayers = computed(()=>{
    return players.value.length
  })

  const getPlayerById = computed(()=>{
    return (id: number) => players.value.find(player => player.id === id)
  })

  // ✅ NUEVA FUNCIÓN: Búsqueda por nombre como computed que retorna función
  const searchPlayersByName = computed(() => {
    return (searchTerm: string): Player[] => {
      if (!searchTerm.trim()) return [];
      
      return players.value.filter(player => 
        player.name.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }
  })

  const clearPlayers = () => {
    players.value = []
  }

  const setPlayers = (newPlayers: Player[]) => {
    players.value = newPlayers
  }

  return {
    // Estado
    players,
    loading,
    error,
    
    // Getters
    totalPlayers,
    getPlayerById,
    
    // Actions
    searchPlayersByName,
    clearPlayers,
    setPlayers,
    fetchPlayers,
    ensurePlayersLoaded
  }


})