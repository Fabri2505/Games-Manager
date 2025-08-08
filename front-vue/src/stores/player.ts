import type { Player } from "@/utils/schema"
import { defineStore } from "pinia"
import { computed, ref } from "vue"

export const usePlayerStore = defineStore('players', () =>{
  const players = ref<Player[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const totalPlayers = computed(()=>{
    return players.value.length
  })

  const getPlayerById = computed(()=>{
    return (id: number) => players.value.find(player => player.id === id)
  })

  const getPlayersByName = computed(() => {
    return (searchTerm: string) => 
      players.value.filter(player => 
        player.nombre.toLowerCase().includes(searchTerm.toLowerCase())
      )
  })

  const addPlayer = (player: Omit<Player, 'id'>) => {
    const newId = players.value.length > 0 
      ? Math.max(...players.value.map(p => p.id)) + 1 
      : 1
    
    const newPlayer: Player = {
      ...player,
      id: newId
    }
    
    players.value.push(newPlayer)
    return newPlayer
  }

  const removePlayer = (id: number) => {
    const index = players.value.findIndex(player => player.id === id)
    if (index > -1) {
      players.value.splice(index, 1)
      return true
    }
    return false
  }

  const clearPlayers = () => {
    players.value = []
  }

  const isValidEmail = (email: string): boolean => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(email)
  }

  const setPlayers = (newPlayers: Player[]) => {
    players.value = newPlayers
  }

  const addPlayerWithValidation = (player: Omit<Player, 'id'>) => {
    error.value = null
    
    // Validaciones
    if (!player.nombre.trim()) {
      error.value = 'El nombre es requerido'
      return null
    }
    
    if (!player.email.trim()) {
      error.value = 'El email es requerido'
      return null
    }
    
    if (!isValidEmail(player.email)) {
      error.value = 'El email no tiene un formato válido'
      return null
    }
    
    // Verificar email único
    if (players.value.some(p => p.email === player.email)) {
      error.value = 'Ya existe un jugador con este email'
      return null
    }
    
    return addPlayer(player)
  }

  return {
    // Estado
    players,
    loading,
    error,
    
    // Getters
    totalPlayers,
    getPlayerById,
    getPlayersByName,
    
    // Actions
    addPlayer,
    addPlayerWithValidation,
    removePlayer,
    clearPlayers,
    setPlayers
  }


})