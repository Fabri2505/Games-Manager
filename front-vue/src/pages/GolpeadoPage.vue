<script setup lang="ts">
import CardJugador from '@/components/CardJugador.vue';
import Header from '@/components/HeaderComponent.vue';
import type { Player } from '@/utils/schema';
import { ArrowLeft, Music } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const jugadores = ref<Player[]>([]);
const route = useRoute();

// Datos adicionales que podrías recibir
const gameData = ref<any>(null)
const rondaData = ref<any>(null)
const serieName = ref<string>('')
const gameName = ref<string>('')

onMounted(() => {
  // Obtener datos del state de la navegación
  const players = history.state?.players as Player[]
  const gameInfo = history.state?.gameData
  const rondaInfo = history.state?.rondaData
  
  // Obtener datos de query params
  serieName.value = route.query.serieName as string || ''
  gameName.value = route.query.gameName as string || ''
  
  if (players && players.length > 0) {
    jugadores.value = players
    console.log('Jugadores recibidos:', players)
  } else {
    // Datos de respaldo si no se reciben jugadores
    console.warn('No se recibieron jugadores, usando datos por defecto')
    jugadores.value = [
      {
        id: 1,
        nombre: 'Carlos Mendoza',
        email: 'carlos.mendoza@email.com'
      },
      // ... otros jugadores por defecto
    ]
  }
  
  if (gameInfo) {
    gameData.value = gameInfo
    console.log('Datos del juego:', gameInfo)
  }
  
  if (rondaInfo) {
    rondaData.value = rondaInfo
    console.log('Datos de la ronda:', rondaInfo)
  }
})

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
          <div class="p-5">
            <h1 class="text-3xl text-center md:text-left font-bold">Mesa de Juego</h1>
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

