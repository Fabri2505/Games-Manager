<script setup lang="ts">
import Header from '@/components/HeaderComponent.vue';
import BotonSwitch from '@/components/HomeGolpeadoP/BotonSwitch.vue';
import { ArrowLeft, Play, Plus, X, Users, Gamepad2 } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const newGame = ref<boolean>(true);
const gameName = ref<string>('');
const playerName = ref<string>('');
const players = ref<string[]>([]);
const selectedSeries = ref<string>('');

// Mock data para series existentes
const existingSeries = ref([
  { id: 1, name: 'Serie Fin de Semana', games: 3, lastPlayed: '2024-08-10' },
  { id: 2, name: 'Torneo Familiar', games: 5, lastPlayed: '2024-08-08' },
  { id: 3, name: 'Liga de Amigos', games: 8, lastPlayed: '2024-08-05' }
]);

const canStartGame = computed(() => {
  if (newGame.value) {
    return gameName.value.trim() !== '' && players.value.length >= 2;
  } else {
    return selectedSeries.value !== '' && gameName.value.trim() !== '' && players.value.length >= 2;
  }
});

const handleGameType = (isNewGame: boolean) => {
  newGame.value = isNewGame;
  // Reset form when switching
  gameName.value = '';
  players.value = [];
  selectedSeries.value = '';
  playerName.value = '';
};

const addPlayer = () => {
  const trimmedName = playerName.value.trim();
  if (trimmedName && !players.value.includes(trimmedName)) {
    players.value.push(trimmedName);
    playerName.value = '';
  }
};

const removePlayer = (index: number) => {
  players.value.splice(index, 1);
};

const handleKeyPress = (event: KeyboardEvent) => {
  if (event.key === 'Enter') {
    addPlayer();
  }
};

const startGame = () => {
  if (canStartGame.value) {
    console.log('Iniciando juego:', {
      isNewGame: newGame.value,
      gameName: gameName.value,
      players: players.value,
      selectedSeries: selectedSeries.value
    });
    // Aquí iría la lógica para iniciar el juego
  }
};
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <Header 
      :titulo="'Configurar Juego'"
      :descrip="'Inicia un nuevo juego o continúa una serie existente'">
      <template #boton_return>
        <button class="p-2 boton_retorno transition-colors duration-200">
          <ArrowLeft class="w-5 h-5" />
        </button>
      </template>
      <template #botones>
        <!-- Espacio para botones adicionales si es necesario -->
      </template>
    </Header>

    <div class="max-w-2xl mx-auto px-4 py-6 space-y-6">
      <!-- Switch de tipo de juego -->
      <BotonSwitch @gameTypeChanged="handleGameType" />

      <!-- Formulario principal -->
      <div class="card_template p-8 bg-white">
        <!-- Header del formulario -->
        <div class="mb-8">
          <div class="flex items-center gap-3 mb-3">
            <div class="p-2 rounded-lg" :class="newGame ? 'bg-blue-100' : 'bg-green-100'">
              <Play v-if="newGame" class="w-6 h-6 text-blue-600" />
              <Gamepad2 v-else class="w-6 h-6 text-green-600" />
            </div>
            <h3 class="text-2xl font-bold text-gray-900">
              {{ newGame ? 'Iniciar Nuevo Juego' : 'Continuar Serie Existente' }}
            </h3>
          </div>
          <p class="text-gray-600 leading-relaxed">
            {{ newGame 
              ? 'Crea un nuevo juego de cartas. Podrás añadir más juegos a esta serie más tarde' 
              : 'Añade un nuevo juego a una serie existente. Las ganancias se acumularán con los juegos anteriores.' 
            }}
          </p>
        </div>

        <!-- Formulario -->
        <form @submit.prevent="startGame" class="space-y-6">
          <!-- Selector de serie existente (solo para continuar serie) -->
          <div v-if="!newGame" class="space-y-2">
            <label for="seriesSelect" class="block text-sm font-semibold text-gray-700">
              Seleccionar serie
            </label>
            <select 
              id="seriesSelect"
              v-model="selectedSeries" 
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 
                focus:ring-blue-500 focus:border-transparent transition-all duration-200
                text-gray-900 placeholder-gray-500"
              required
            >
              <option value="" disabled>Selecciona una serie...</option>
              <option 
                v-for="series in existingSeries" 
                :key="series.id" 
                :value="series.name"
              >
                {{ series.name }} ({{ series.games }} juegos)
              </option>
            </select>
          </div>

          <!-- Nombre del juego -->
          <div class="space-y-2">
            <label for="gameName" class="block text-sm font-semibold text-gray-700">
              {{ newGame ? 'Nombre del juego' : 'Nombre del nuevo juego' }}
            </label>
            <input 
              id="gameName"
              v-model="gameName"
              type="text" 
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 
                focus:ring-blue-500 focus:border-transparent transition-all duration-200
                text-gray-900 placeholder-gray-500"
              placeholder="Ej: Golpeado para la quincena"
              required
            >
          </div>

          <!-- Jugadores -->
          <div class="space-y-2">
            <label class="form-block text-sm font-semibold text-gray-700 flex items-center gap-2">
              <Users class="w-4 h-4" />
              {{ newGame ? 'Jugadores' : 'Jugadores para este juego' }}
            </label>
            
            <!-- Input para añadir jugador -->
            <div class="flex gap-3 mb-4">
              <input 
                v-model="playerName"
                type="text" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 
                focus:ring-blue-500 focus:border-transparent transition-all duration-200
                text-gray-900 placeholder-gray-500 flex-1"
                placeholder="Nombre del jugador"
                @keypress="handleKeyPress"
              >
              <button 
                type="button"
                @click="addPlayer"
                :disabled="!playerName.trim()"
                class="btn bg-green-600 text-white hover:bg-green-700 
                 focus:ring-green-500 disabled:bg-gray-400 disabled:hover:bg-gray-400 
                 rounded-md flex items-center gap-2 px-4 py-2 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <Plus class="w-4 h-4" />
                Añadir
              </button>
            </div>

            <!-- Lista de jugadores añadidos -->
            <div v-if="players.length > 0" class="space-y-2">
              <div class="text-sm font-medium text-gray-700 mb-3">
                Jugadores añadidos ({{ players.length }}):
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div 
                  v-for="(player, index) in players" 
                  :key="index"
                  class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2 group hover:bg-gray-100 transition-colors"
                >
                  <span class="text-gray-900 font-medium">{{ player }}</span>
                  <button 
                    type="button"
                    @click="removePlayer(index)"
                    class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded opacity-0 group-hover:opacity-100"
                    title="Eliminar jugador"
                  >
                    <X class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>

            <!-- Mensaje de validación -->
            <div class="mt-3">
              <p 
                v-if="players.length < 2" 
                class="text-sm text-amber-600 bg-amber-50 px-3 py-2 rounded-lg border border-amber-200"
              >
                ⚠️ Añade al menos 2 jugadores para continuar
              </p>
              <p 
                v-else 
                class="text-sm text-green-600 bg-green-50 px-3 py-2 rounded-lg border border-green-200"
              >
                ✅ ¡Perfecto! Tienes {{ players.length }} jugadores listos
              </p>
            </div>
          </div>

          <!-- Botón de acción -->
          <button 
            type="submit"
            :disabled="!canStartGame"
            class="btn bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 
            w-full flex items-center justify-center gap-3 py-3 text-lg font-semibold 
            disabled:opacity-50 disabled:cursor-not-allowed rounded-md"
          >
            <Play v-if="newGame" class="w-5 h-5" />
            <Gamepad2 v-else class="w-5 h-5" />
            {{ newGame ? 'Iniciar Nuevo Juego' : 'Continuar Serie' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Botón de retorno */
.boton_retorno {
  border-radius: 12px;
  color: #6b7280;
}

.boton_retorno:hover {
  background-color: #f3f4f6;
  color: #374151;
}

/* Card principal */
.card_template {
  border: 1px solid #e5e7eb;
  border-radius: 16px;
  box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
  transition: box-shadow 0.2s ease-in-out;
}

.card_template:hover {
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
}

</style>