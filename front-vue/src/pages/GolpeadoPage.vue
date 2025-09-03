<script setup lang="ts">
import CardJugador from '@/components/CardJugador.vue';
import Header from '@/components/HeaderComponent.vue';
import type { Participante, Player } from '@/utils/schema';
import { ArrowLeft, Music } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { gameService } from '@/service/GameService';

const jugadores = ref<Player[]>([]);
const route = useRoute();
const cantRonda = ref<number>(0); // Número de ronda actual

// Datos adicionales que podrías recibir
const gameData = ref<any>(null)
const rondaData = ref<any>(null)
const serieName = ref<string>('')
const gameName = ref<string>('')

onMounted(async () => {
  // Obtener datos del state de la navegación
  try {

    // idGame desde params
    const idGame = Number(route.params.idGame);
    // Parsear datos del state
    const playersJson = history.state?.players as string
    const gameDataJson = history.state?.gameData as string  
    const rondaDataJson = history.state?.rondaData as string
    
    // Obtener query params
    serieName.value = route.query.serieName as string || ''
    gameName.value = route.query.gameName as string || ''
    
    // Parsear players
    if (playersJson) {
      jugadores.value = JSON.parse(playersJson) as Player[]
      console.log('Jugadores recibidos:', jugadores.value)
    }
    
    // Parsear gameData  
    if (gameDataJson) {
      gameData.value = JSON.parse(gameDataJson)
      console.log('Datos del juego:', gameData.value)
    }
    
    // Parsear rondaData
    if (rondaDataJson) {
      rondaData.value = JSON.parse(rondaDataJson)  
      console.log('Datos de la ronda:', rondaData.value)
    }
    
    // Fallback si no hay datos
    if (!jugadores.value.length) {

      const lastRondaResponse = await gameService.getLastRonda(idGame);

      if (lastRondaResponse.success) {
        rondaData.value = lastRondaResponse.data
        cantRonda.value = lastRondaResponse.nro_ronda || 1
        console.log('Última ronda obtenida del servidor:', rondaData.value)
      } else {
        console.error('Error al obtener la última ronda:', lastRondaResponse.message)
      }

      console.warn('No se recibieron jugadores')

      const participaciones = lastRondaResponse.data.participantes;
      jugadores.value = participaciones.map((p: Participante) => {
        return {
          id: p.user.id,
          email: p.user.email,
          nombre: `${p.user.name} ${p.user.ape}`
          // Otros campos que puedas necesitar
        } as Player
      })

      const analityData = await gameService.getAnalitycs(idGame);

      console.log('Datos analíticos de la ronda:', analityData)
      
    }
    
  } catch (error) {
    console.error('Error al parsear datos:', error)
  }
  
})

const nuevaRonda = async () => {
  // Lógica para iniciar una nueva ronda
  cantRonda.value += 1;
  // Aquí podrías agregar lógica para reiniciar estados, repartir cartas, etc.
}

</script>

<template>
    <Header 
      :titulo="'Golpeado'" >
      <template #boton_return>
        <button class="p-2 boton_retorno"><ArrowLeft/></button>
      </template>
      <template #botones>
        <button class="boton_header"> <Music class="w-5 m-3" /></button>
        <button class="flex p-2 gap-2 boton_header">Play</button>
      </template>
    </Header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 flex-grow">
      <div class="lg:col-span-2">
        <div class="rounded-lg card_template text-card-foreground shadow-2xs h-full">
          <!-- bg-[#FFE27A] -->
          <div class="flex justify-between p-5">
            <h1 class="text-3xl text-center md:text-left font-bold">Mesa de Juego</h1>
            <div v-if="cantRonda>0" class="flex gap-2 items-center">
              <p>Ronda: {{ cantRonda }}</p>
              <button class="bg-black text-white p-1 rounded-md"
                @click="nuevaRonda">Nueva Ronda</button>
            </div>
            
          </div>
          <!-- #B3F5B9 -->
          <div class="m-auto">
            <div class="flex flex-wrap gap-4 justify-center md:justify-start player-list">
              <CardJugador
                v-for="jugador in jugadores"
                :key="jugador.id"
                :player="jugador"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="space-y-6">
        <div class="rounded-lg card_template shadow-2xs">
          <div class="p-6 py-8 text-center">
            <p class="text-muted-foreground">Añade jugadores para comenzar la partida</p>
          </div>
        </div>
      </div>
      
    </div>
</template>

<style scoped>
  .boton_retorno{
    border-radius: 10px;
  }
  .boton_retorno:hover{
    background-color: #EBEBEB;
  }

  .boton_header{
    background-color: white;
    border: 1px solid #EBEBEB ;
    border-radius: 10px;
  }

  .card_template{
    border: 0.5px solid #EBEBEB;
    border-radius: 15px;
  }

  .player-list {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }

  h2 {
    color: #1f2937;
    margin-bottom: 20px;
    text-align: center;
  }
  
</style>

