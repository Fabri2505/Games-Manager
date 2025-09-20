<script setup lang="ts">
import CardJugador from '@/components/CardJugador.vue';
import Header from '@/components/HeaderComponent.vue';
import CardEstadist from '@/components/GolpeadoPage/CardEstadist.vue';
import StatsCard from '../components/GolpeadoPage/DistribucionCard.vue';
import type { Player , Participante, User } from '@/utils/schema_participante';
import { ArrowLeft, ChartColumnBig, Clock, Guitar, Music, Sparkles } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { gameService } from '@/service/GameService';
import { rondaService, ValidationError } from '@/service/RondaService';

interface GolpeadoPageState {
  rondaId: number|null;
  gameId: number|null;
}

const jugadores = ref<Player[]>([]);
const idJugadorSeleccionado = ref<number | null>(null);
const route = useRoute();
const cantRonda = ref<number>(0); // Número de ronda actual
const golpeadoState = ref<GolpeadoPageState>({
  rondaId: null,
  gameId: null
});

// Datos adicionales que podrías recibir
const gameName = ref<string>('')
const racha = ref<boolean>(true) // Racha del jugador principal

const gameStateItems = computed(() => [
  {
    label: 'Ronda',
    value: cantRonda.value.toString()
  },
  {
    label: 'Jugadores',
    value: `${jugadores.value.length} activos`
  },
  {
    label: 'Tiempo',
    value: '15 minutos'
  }
]);

const leaderItems = [
  {
    label: 'Nombre',
    value: 'Pedro Pablo'
  },
  {
    label: 'Puntaje',
    value: '+51',
    valueClass: 'text-green-600'
  },
  {
    label: 'Victorias',
    value: '6 (55%)'
  }
];


onMounted(async () => {
  // Obtener datos del state de la navegación
  try {

    // idGame desde params
    golpeadoState.value.gameId = Number(route.params.idGame);

    // Fallback si no hay datos
    const lastRondaResponse = await gameService.getLastRonda(golpeadoState.value.gameId);

    console.log('Respuesta de la última ronda:', lastRondaResponse)

    gameName.value = lastRondaResponse.game.name;

    if (lastRondaResponse.success) {
      // rondaData.value = lastRondaResponse.data
      cantRonda.value = lastRondaResponse.nro_ronda || 0
      // console.log('Última ronda obtenida del servidor:', rondaData.value)
    } else {
      console.error('Error al obtener la última ronda:', lastRondaResponse.message)
    }

    console.warn('No se recibieron jugadores')

    const participaciones = lastRondaResponse.data.participantes;
    jugadores.value = participaciones.map((p: Participante<User>) => {
      return {
        id: p.user.id,
        name: `${p.user.name} ${p.user.ape}`,
        email: p.user.email
        // Otros campos que puedas necesitar
      } as Player
    })

    // jugadores.value = [
    //   ...jugadores.value,
    //   // Agregar jugadores ficticios si es necesario
    //   { id: 999, email: 'dsd' , nombre: 'Jugador Ficticio' } ,
    //   { id: 998, email: 'dsd' , nombre: 'Jugador Ficticio 2' },
    //   { id: 997, email: 'dsd' , nombre: 'Jugador Ficticio 3' } ,
    //   { id: 996, email: 'dsd' , nombre: 'Jugador Ficticio 4', selected: true }
    // ]

    const analityData = await gameService.getAnalitycs(golpeadoState.value.gameId);

    console.log('Datos analíticos de la ronda:', analityData)
    
  } catch (error) {
    console.error('Error al parsear datos:', error)
  }
  
})

const marcarGanador = computed(() => {
  return idJugadorSeleccionado.value !== null;
});

const setWinner = async () => {
  const rondaId = golpeadoState.value.rondaId; // Aquí deberías obtener el ID real de la ronda actual
  const gameId = golpeadoState.value.gameId; // Aquí deberías obtener el ID real del juego
  const responseWinner = await rondaService.setWinner(rondaId??0, idJugadorSeleccionado.value!, gameId??0);
  console.log('Respuesta al setear ganador:', responseWinner);
}

const nuevaRonda = async () => {
  // Lógica para iniciar una nueva ronda
  try{
    console.log('Iniciando nueva ronda...');
    console.log('ID del juego:', golpeadoState.value.gameId);
    console.log('Jugadores participantes:', jugadores.value.map(j=>j.id));

    const rondaCreate = await rondaService.createRonda({
      game_id:golpeadoState.value.gameId??0,
      participantes:jugadores.value.map(j=>j.id)
    });

    cantRonda.value += 1;
  } catch (error) {

    console.log('Error al crear la ronda:', error);
    // Manejo específico para errores de validación
    if (error instanceof ValidationError) {
      console.error('❌ Errores de validación detectados:');
      
      // Resumen de errores
      console.error(`📋 Resumen: ${error.getErrorSummary()}`);
      
      // Aquí podrías mostrar una notificación al usuario
      // Por ejemplo: mostrarNotificacion('error', error.getErrorSummary());
      
    } else if (error instanceof Error) {
      // Otros tipos de errores
      console.error('❌ Error al crear la ronda:', error.message);
      
      // Aquí podrías mostrar una notificación genérica
      // Por ejemplo: mostrarNotificacion('error', 'Error inesperado al crear la ronda');
      
    } else {
      // Error completamente inesperado
      console.error('❌ Error inesperado:', error);
    }
  }
  // Aquí podrías agregar lógica para reiniciar estados, repartir cartas, etc.
}

const onPlayerSelected = (player: { id: number, nombre: string }) => {
  console.log('Jugador seleccionado:', player);

  let jugadorYaSeleccionado:boolean = false;
  jugadores.value.forEach(j => {
    if (j.id === player.id) {
      j.selected = !j.selected;
      jugadorYaSeleccionado = j.selected; // Guardamos el estado final
    } else {
      j.selected = false;
    }
  });
  // Actualizamos idJugadorSeleccionado basado en si hay algún jugador seleccionado
  idJugadorSeleccionado.value = jugadorYaSeleccionado ? player.id : null;

  console.log('Estado actualizado de jugadores:', jugadores.value);
  console.log('ID del jugador seleccionado:', idJugadorSeleccionado.value);

};

// Datos de ejemplo para victorias
const victoryStats = ref([
  { id: 1, name: 'PP', value: 6, percentage: 55 },
  { id: 2, name: 'JM', value: 5, percentage: 20 },
  { id: 3, name: 'AL', value: 3, percentage: 25 }
])

// Datos de ejemplo para balance
const balanceStats = ref([
  { id: 1, name: 'PP', value: 51 },
  { id: 2, name: 'JM', value: -51 },
  { id: 3, name: 'AL', value: 0 }
])

</script>

<template>
    <Header 
      :titulo="gameName" >
      <template #boton_return>
        <button class="p-2 boton_retorno"><ArrowLeft/></button>
      </template>
      <template #botones>
        <button class="boton_header"> <Music class="w-5 m-3" /></button>
      </template>
    </Header>
    <!-- Sección de estadísticas superior -->
    <div v-if="racha" class="rounded-2xl p-6 bg-gradient-to-r from-red-500 to-red-600 shadow-lg mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <svg class="w-10 h-10 text-yellow-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13.5.67s.74 2.65.74 4.8c0 2.06-1.35 3.73-3.41 3.73-2.07 0-3.63-1.67-3.63-3.73l.03-.36C5.21 7.51 4 10.62 4 14c0 4.42 3.58 8 8 8s8-3.58 8-8C20 8.61 17.41 3.8 13.5.67zM11.71 19c-1.78 0-3.22-1.4-3.22-3.14 0-1.62 1.05-2.76 2.81-3.12 1.77-.36 3.6-1.21 4.62-2.58.39 1.29.28 2.16.28 2.16-.36 2.73-2.18 4.78-4.49 6.68z"/>
                </svg>
                <div>
                    <h3 class="text-white font-bold text-2xl">Pedro Pablo</h3>
                    <p class="text-white/90 text-lg">🔥 2 victorias seguidas</p>
                </div>
            </div>
            <div class="bg-white/20 rounded-full px-6 py-3">
                <span class="text-white font-bold text-xl">🔥</span>
            </div>
        </div>
    </div>

    <!-- Versión compacta con 2 cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
      <CardEstadist title="Estado de Partida" :icon="Sparkles" colorScheme="green" :items="gameStateItems"/>
      <CardEstadist title="Líder Actual" :icon="Guitar" colorScheme="blue" :items="leaderItems"/>
    </div>

    <!-- Contenido principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
          <div class="rounded-lg card_template text-card-foreground shadow-2xs h-full">
            <!-- bg-[#FFE27A] -->
            <div class="flex justify-between p-5">
              <h1 class="text-3xl text-center md:text-left font-bold">Mesa de Juego</h1>
              <div class="flex gap-2 items-center">
                <button v-if="marcarGanador" class="bg-black text-white p-2 rounded-md"
                  @click="nuevaRonda">Marcar Winner</button>
                <button class="bg-black text-white p-2 rounded-md"
                  @click="nuevaRonda">Nueva Ronda</button>
              </div>
              
            </div>
            <!-- #B3F5B9 -->
            <div class="m-auto">
              <div class="flex flex-wrap gap-2 md:gap-5 justify-center player-list">
                <CardJugador v-for="jugador in jugadores" :key="jugador.id" :player="jugador" @playerSelected="onPlayerSelected"/>
              </div>
            </div>
          </div>
        </div>
        
        <div class="space-y-4">
            <!-- Distribución de Victorias -->
            <StatsCard
              title="Distribución de Victorias"
              :icon="ChartColumnBig"
              icon-color="text-blue-500"
              type="victories"
              :players="victoryStats"
            />

            <!-- Balance Juego Actual -->
            <StatsCard
              title="Balance Juego Actual"
              :icon="Clock"
              icon-color="text-amber-500"
              type="balance"
              :players="balanceStats"
            />
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

