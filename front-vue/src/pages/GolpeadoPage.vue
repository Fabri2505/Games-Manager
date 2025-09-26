<script setup lang="ts">
import CardJugador from '@/components/CardJugador.vue';
import Header from '@/components/HeaderComponent.vue';
import CardEstadist from '@/components/GolpeadoPage/CardEstadist.vue';
import StatsCard from '../components/GolpeadoPage/DistribucionCard.vue';
import type { Player , Participante, User } from '@/utils/schema_participante';
import { ArrowLeft, ChartColumnBig, Clock, Flame, Guitar, Music, Sparkles } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { gameService } from '@/service/GameService';
import { rondaService, ValidationError } from '@/service/RondaService';
import router from '@/router';
import { useConfirm } from "primevue/useconfirm";


interface GolpeadoPageState {
  rondaId: number;
  gameId: number;
  hora_ini_ronda?: string;
}

const confirm = useConfirm();
let gameName:string = '';
const jugadores = ref<Player[]>([]);
const idJugadorSeleccionado = ref<number | null>(null);
const route = useRoute();
const cantRonda = ref<number>(0); // Número de ronda actual
const golpeadoState = ref<GolpeadoPageState>({
  rondaId: 0,
  gameId: 0,
  hora_ini_ronda: undefined
});

const racha = ref<boolean>(false) // Racha del jugador principal

// Agregar función para formatear hora
const formatearHora12 = (horaString?: string): string => {
  if (!horaString) return '--:-- --';
  
  const [horas, minutos, segundos] = horaString.split(':');
  const hora24 = parseInt(horas);
  const hora12 = hora24 % 12 || 12;
  const ampm = hora24 >= 12 ? 'PM' : 'AM';
  
  return `${hora12}:${minutos} ${ampm}`;
};

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
    label: 'Hora inicio Ronda',
    value: formatearHora12(golpeadoState.value.hora_ini_ronda)
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

    // Fallback si no hay datos
    const lastRondaResponse = await gameService.getLastRonda(Number(route.params.idGame));

    console.log('Respuesta de la última ronda:', lastRondaResponse)

    if (lastRondaResponse.success) {
      gameName = lastRondaResponse.game.name;
      golpeadoState.value.gameId = lastRondaResponse.game.id;
      golpeadoState.value.rondaId = lastRondaResponse.data.id;
      golpeadoState.value.hora_ini_ronda = lastRondaResponse.data.hora_ini;
      cantRonda.value = lastRondaResponse.nro_ronda

      jugadores.value = lastRondaResponse.data.participantes.map((p: Participante<User>) => {
        return {
          id: p.user.id,
          name: `${p.user.name} ${p.user.ape}`,
          email: p.user.email
          // Otros campos que puedas necesitar
        } as Player
      });

    } else {
      console.error('Error al obtener la última ronda:', lastRondaResponse.message)
    }

    const analityData = await gameService.getAnalitycs(golpeadoState.value.gameId);

    console.log('Datos analíticos de la ronda:', analityData)
    
  } catch (error) {
    console.error('Error al parsear datos:', error)
    router.push('/not-found');
  }
  
})

const setWinner = async () => {
  const rondaId = golpeadoState.value.rondaId; // Aquí deberías obtener el ID real de la ronda actual
  const gameId = golpeadoState.value.gameId; // Aquí deberías obtener el ID real del juego
  const responseWinner = await rondaService.setWinner(rondaId, idJugadorSeleccionado.value!, gameId);
  console.log('Respuesta al setear ganador:', responseWinner);
}

const confirmarNuevaRonda = () => {
  if (idJugadorSeleccionado.value){
    nuevaRonda();
  }else{
    confirm.require({
      message: '¿Estás seguro de iniciar una nueva ronda? Esto finalizará la ronda actual.',
      header: 'Confirmar Nueva Ronda',
      icon: 'pi pi-exclamation-triangle',
      rejectProps: {
        label: 'Cancel',
        severity: 'secondary',
        outlined: true
      },
      acceptProps: {
        label: 'Save'
      },
      accept: () => {
        nuevaRonda();
      },
      reject: () => {
        // Acción en caso de rechazo (opcional)
        console.log('Acción de nueva ronda cancelada.');
      }
    });
  }
};

const nuevaRonda = async () => {
  // Lógica para iniciar una nueva ronda
  try{
    const rondaCreate = await rondaService.createRonda({
      game_id:golpeadoState.value.gameId??0,
      participantes:jugadores.value.map(j=>j.id)
    });

    // reasignamos la nueva ronda
    golpeadoState.value.rondaId = rondaCreate.id;
    console.log('Nueva ronda creada:', rondaCreate.id);
    
    jugadores.value.forEach(j => j.selected = false);

    cantRonda.value += 1;
  } catch (error) {
    console.log('Error al crear la ronda:', error);
    // Manejo específico para errores de validación
    if (error instanceof ValidationError) {
      console.error('❌ Errores de validación detectados:');
      console.error(`📋 Resumen: ${error.getErrorSummary()}`);
      
    } else if (error instanceof Error) {
      console.error('❌ Error al crear la ronda:', error.message);
    } else {
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
  <div v-if="golpeadoState.gameId" class="m-5">
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
    <div v-if="racha" class="rounded-2xl p-6 bg-gradient-to-br from-red-500 via-red-600 to-red-700 shadow-xl mb-6 border border-red-400/30">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <!-- Contenedor del icono con efecto glow -->
          <div class="relative">
            <div class="absolute inset-0 bg-yellow-300/30 rounded-full blur-md"></div>
            <Flame class="w-12 h-12 text-yellow-300 relative z-10" stroke-width="2.5"/>
          </div>
          
          <div>
            <h3 class="text-white font-bold text-2xl mb-1">Pedro Pablo</h3>
            <div class="flex items-center gap-2">
              <div class="flex">
                <span class="text-2xl">🔥</span>
                <span class="text-2xl animate-pulse">🔥</span>
              </div>
              <p class="text-white/95 text-lg font-medium">2 juegos ganados seguidos</p>
            </div>
            <p class="text-white/70 text-sm mt-1">¡Está en racha!</p>
          </div>
        </div>
        
        <!-- Badge de racha con contexto de juegos -->
        <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-3 border border-white/30">
          <div class="text-center">
            <div class="text-white font-bold text-2xl">2</div>
            <div class="text-white/80 text-xs font-medium">RONDAS</div>
          </div>
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
              <button v-if="idJugadorSeleccionado" class="bg-amber-400 text-white p-2 rounded-md"
                @click="setWinner">Marcar Winner</button>
              <button class="bg-black text-white p-2 rounded-md"
                @click="confirmarNuevaRonda()">Nueva Ronda</button>
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
  </div>
  <ConfirmDialog></ConfirmDialog>
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

