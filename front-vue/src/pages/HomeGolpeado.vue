<script setup lang="ts">
import Header from '@/components/HeaderComponent.vue';
import BotonSwitch from '@/components/HomeGolpeadoP/BotonSwitch.vue';
import { ArrowLeft, Play, Plus, X, Users, Gamepad2, Search } from 'lucide-vue-next';
import { ref, computed, onMounted, watch, nextTick } from 'vue';
import { usePlayerStore } from '../stores/player';
import type { Player, Serie } from '@/utils/schema';

const playerStore = usePlayerStore();

const newGame = ref<boolean>(true);
const gameName = ref<string>('');
const playerName = ref<string>('');
const selectedPlayers = ref<Player[]>([]);
const selectedSeries = ref<string>('');

const series = ref<Serie[]>([]);

// Estados para autocompletado
const showDropdown = ref<boolean>(false);
const playerInputRef = ref<HTMLInputElement | null>(null);

const canStartGame = computed(() => {
  if (newGame.value) {
    return gameName.value.trim() !== '' && selectedPlayers.value.length >= 2;
  } else {
    return selectedSeries.value !== '' && gameName.value.trim() !== '' && selectedPlayers.value.length >= 2;
  }
});

const filteredPlayers = computed(() => {
  if (!playerName.value.trim()) return [];
  
  // Usar la función de búsqueda del store
  const searchResults = playerStore.searchPlayersByName(playerName.value);
  
  // Excluir jugadores ya seleccionados
  return searchResults
    .filter(player => !selectedPlayers.value.some(selected => selected.id === player.id))
    .slice(0, 5); // Limitar a 5 resultados
});

// 👀 Watchers para manejar el dropdown
watch(playerName, (newValue) => {
  showDropdown.value = newValue.trim() !== '' && filteredPlayers.value.length > 0;
});

watch(filteredPlayers, (newFiltered) => {
  showDropdown.value = playerName.value.trim() !== '' && newFiltered.length > 0;
});

onMounted(async () => {
  console.log('HomeGolpeado montado');
  try {
    await playerStore.ensurePlayersLoaded(); // ✅ Esperar a que termine
    console.log('Jugadores cargados:', playerStore.players);
    console.log('Total de jugadores:', playerStore.totalPlayers);
  } catch (error) {
    console.error('Error al cargar jugadores:', error);
  }
})

// 👥 Gestión de Jugadores
const selectPlayerFromDropdown = (player: Player) => {
  // Verificar que no esté ya seleccionado
  if (!selectedPlayers.value.some(selected => selected.id === player.id)) {
    selectedPlayers.value.push(player);
  }
  
  // Limpiar input y cerrar dropdown
  playerName.value = '';
  showDropdown.value = false;
  
  // Enfocar el input para continuar agregando jugadores
  nextTick(() => {
    playerInputRef.value?.focus();
  });
};

const addPlayerFromSearch = () => {
  const trimmedName = playerName.value.trim();
  if (!trimmedName) return;

  // Buscar si el jugador ya existe exactamente
  const existingPlayer = playerStore.players.find(player => 
    player.nombre.toLowerCase() === trimmedName.toLowerCase()
  );

  if (existingPlayer) {
    selectPlayerFromDropdown(existingPlayer);
  } else {
    // Si no existe, podrías crear uno nuevo o mostrar un mensaje
    console.log('🤔 Jugador no encontrado:', trimmedName);
    // Por ahora solo limpiamos el input
    playerName.value = '';
  }
};

const removePlayerFromSelection = (playerId: number) => {
  const index = selectedPlayers.value.findIndex(player => player.id === playerId);
  if (index > -1) {
    selectedPlayers.value.splice(index, 1);
  }
};


// Event handlers
const handleGameType = (isNewGame: boolean) => {
  newGame.value = isNewGame;
  gameName.value = '';
  selectedPlayers.value = [];
  selectedSeries.value = '';
  playerName.value = '';
  showDropdown.value = false;
};

// ⌨️ Manejo de teclado
const handleKeyPress = (event: KeyboardEvent) => {
  if (event.key === 'Enter') {
    event.preventDefault();
    
    if (filteredPlayers.value.length > 0) {
      // Si hay sugerencias, seleccionar la primera
      selectPlayerFromDropdown(filteredPlayers.value[0]);
    } else {
      // Si no hay sugerencias, intentar agregar como nuevo
      addPlayerFromSearch();
    }
  } else if (event.key === 'Escape') {
    showDropdown.value = false;
    playerInputRef.value?.blur();
  } else if (event.key === 'ArrowDown' && filteredPlayers.value.length > 0) {
    event.preventDefault();
    // Aquí podrías implementar navegación con flechas
    showDropdown.value = true;
  }
};

const handleInputFocus = () => {
  if (playerName.value.trim() && filteredPlayers.value.length > 0) {
    showDropdown.value = true;
  }
};

// 🖱️ Clicks fuera del dropdown
const handleClickOutside = (event: Event) => {
  const target = event.target as HTMLElement;
  const dropdown = document.querySelector('.players-dropdown');
  const input = playerInputRef.value;
  
  if (dropdown && input && !dropdown.contains(target) && !input.contains(target)) {
    showDropdown.value = false;
  }
};

// 🧹 Limpiar formulario
const clearForm = () => {
  gameName.value = '';
  selectedPlayers.value = [];
  selectedSeries.value = '';
  playerName.value = '';
  showDropdown.value = false;
};

// 🔄 Actualizar jugador en tiempo real (si viene de otro componente)
const refreshPlayers = async () => {
  try {
    await playerStore.fetchPlayers();
    console.log('🔄 Jugadores actualizados');
  } catch (error) {
    console.error('Error al actualizar jugadores:', error);
  }
};


const startGame = () => {
  if (canStartGame.value) {
    console.log('Iniciando juego:', {
      isNewGame: newGame.value,
      gameName: gameName.value,
      players: selectedPlayers.value,
      selectedSeries: selectedSeries.value
    });
    // Aquí iría la lógica para iniciar el juego
  }
};



</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- 🎯 Header del componente -->
    <Header 
      :titulo="'Configurar Juego'"
      :descrip="'Inicia un nuevo juego o continúa una serie existente'"
    >
      <template #boton_return>
        <button class="p-2 boton_retorno transition-colors duration-200">
          <ArrowLeft class="w-5 h-5" />
        </button>
      </template>
      <template #botones>
        <!-- Botón para refrescar jugadores -->
        <button 
          @click="refreshPlayers"
          :disabled="playerStore.loading"
          class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
          title="Actualizar jugadores"
        >
          <div 
            v-if="playerStore.loading" 
            class="animate-spin rounded-full h-5 w-5 border-2 border-gray-600 border-t-transparent"
          ></div>
          <Users v-else class="w-5 h-5" />
        </button>
      </template>
    </Header>

    <div class="max-w-2xl mx-auto px-4 py-6 space-y-6">
      <!-- 🔄 Switch de tipo de juego -->
      <BotonSwitch @gameTypeChanged="handleGameType" />

      <!-- 📋 Formulario principal -->
      <div class="card_template p-8 bg-white">
        <!-- 🎯 Header dinámico del formulario -->
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

        <!-- 📝 Formulario -->
        <form @submit.prevent="startGame" class="space-y-6">
          <!-- 🎮 Selector de serie existente (solo para continuar serie) -->
          <div v-if="!newGame" class="space-y-2">
            <label for="seriesSelect" class="block text-sm font-semibold text-gray-700">
              Seleccionar serie existente
            </label>
            <select 
              id="seriesSelect"
              v-model="selectedSeries" 
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 
                focus:ring-green-500 focus:border-transparent transition-all duration-200
                text-gray-900"
              required
            >
              <option value="" disabled>Selecciona una serie...</option>
              <option 
                v-for="serie in series" 
                :key="serie.id" 
                :value="serie.name"
              >
                {{ serie.name }} ({{ serie.gameId }} juegos) - Último: {{ serie.name }}
              </option>
            </select>
            <p class="text-sm text-gray-500">
              💡 Los puntos se acumularán con los juegos anteriores de esta serie
            </p>
          </div>

          <!-- ✏️ Nombre del juego -->
          <div class="space-y-2">
            <label for="gameName" class="block text-sm font-semibold text-gray-700">
              {{ newGame ? 'Nombre del juego' : 'Nombre del nuevo juego en la serie' }}
            </label>
            <input 
              id="gameName"
              v-model="gameName"
              type="text" 
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 
                focus:border-transparent transition-all duration-200 text-gray-900"
              :class="newGame ? 'focus:ring-blue-500' : 'focus:ring-green-500'"
              :placeholder="newGame ? 'Ej: Golpeado de fin de semana' : 'Ej: Ronda 4 - Revancha'"
              required
            >
          </div>

          <!-- 👥 Sección de jugadores -->
          <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
              <Users class="w-4 h-4" />
              {{ newGame ? 'Jugadores' : 'Jugadores para este juego' }}
            </label>
            
            <!-- ❌ Error del store -->
            <div v-if="playerStore.error" class="text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg border border-red-200">
              {{ playerStore.error }}
            </div>
            
            <!-- 🔍 Input para añadir jugador con autocompletado -->
            <div class="flex gap-3 mb-4 relative">
              <div class="relative flex-1">
                <div class="relative">
                  <input 
                    ref="playerInputRef"
                    v-model="playerName"
                    type="text" 
                    class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 
                      focus:ring-blue-500 focus:border-transparent transition-all duration-200
                      text-gray-900 placeholder-gray-500"
                    :class="{ 'border-blue-500 ring-1 ring-blue-500': showDropdown }"
                    placeholder="Buscar jugador por nombre..."
                    autocomplete="off"
                    @keydown="handleKeyPress"
                    @focus="handleInputFocus"
                  >
                  <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                    <Search v-if="!playerStore.loading" class="w-4 h-4 text-gray-400" />
                    <div v-else class="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"></div>
                  </div>
                </div>

                <!-- 📋 Dropdown de sugerencias -->
                <div 
                  v-if="showDropdown && filteredPlayers.length > 0"
                  class="players-dropdown absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 max-h-60 overflow-y-auto"
                >
                  <div 
                    v-for="player in filteredPlayers" 
                    :key="player.id"
                    @click="selectPlayerFromDropdown(player)"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-100 last:border-b-0"
                  >
                    <!-- Avatar con inicial -->
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                      <span class="text-blue-600 font-medium text-sm">
                        {{ player.nombre.charAt(0).toUpperCase() }}
                      </span>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-900 truncate">
                        {{ player.nombre }}
                      </p>
                      <p v-if="player.email" class="text-xs text-gray-500 truncate">
                        {{ player.email }}
                      </p>
                    </div>
                    <Plus class="w-4 h-4 text-gray-400 flex-shrink-0" />
                  </div>
                </div>

                <!-- 💡 Mensaje de ayuda cuando no hay resultados -->
                <div 
                  v-if="playerName.trim() && filteredPlayers.length === 0 && !playerStore.loading"
                  class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 p-4 text-center text-gray-500"
                >
                  <p class="text-sm">No se encontraron jugadores con ese nombre</p>
                  <p class="text-xs mt-1">Presiona Enter para crear uno nuevo (próximamente)</p>
                </div>
              </div>

              <button 
                type="button"
                @click="addPlayerFromSearch"
                :disabled="!playerName.trim()"
                class="btn bg-green-600 text-white hover:bg-green-700 
                  focus:ring-green-500 disabled:bg-gray-400 disabled:hover:bg-gray-400 
                  rounded-lg flex items-center gap-2 px-4 py-2 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <Plus class="w-4 h-4" />
                Añadir
              </button>
            </div>

            <!-- 📋 Lista de jugadores seleccionados -->
            <div v-if="selectedPlayers.length > 0" class="space-y-2">
              <div class="text-sm font-medium text-gray-700 mb-3">
                Jugadores seleccionados ({{ selectedPlayers.length }}):
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div 
                  v-for="player in selectedPlayers" 
                  :key="player.id"
                  class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2 group hover:bg-gray-100 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                      <span class="text-blue-600 font-medium text-xs">
                        {{ player.nombre.charAt(0).toUpperCase() }}
                      </span>
                    </div>
                    <span class="text-gray-900 font-medium">{{ player.nombre }}</span>
                  </div>
                  <button 
                    type="button"
                    @click="removePlayerFromSelection(player.id)"
                    class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded opacity-0 group-hover:opacity-100"
                    title="Eliminar jugador"
                  >
                    <X class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>

            <!-- ⚠️ Mensaje de validación -->
            <div class="mt-3">
              <p 
                v-if="selectedPlayers.length < 2" 
                class="text-sm text-amber-600 bg-amber-50 px-3 py-2 rounded-lg border border-amber-200"
              >
                ⚠️ Añade al menos 2 jugadores para continuar
              </p>
              <p 
                v-else 
                class="text-sm text-green-600 bg-green-50 px-3 py-2 rounded-lg border border-green-200"
              >
                ✅ ¡Perfecto! Tienes {{ selectedPlayers.length }} jugadores listos
              </p>
            </div>

            <!-- 📊 Info del store -->
            <div v-if="playerStore.totalPlayers > 0" class="text-xs text-gray-500 mt-2">
              Total de jugadores disponibles: {{ playerStore.totalPlayers }}
            </div>
          </div>

          <!-- 🎮 Botones de acción -->
          <div class="flex gap-3 pt-4">
            <button 
              type="submit"
              :disabled="!canStartGame"
              class="btn text-white focus:ring-blue-500 flex-1 py-3 text-lg font-semibold 
                disabled:opacity-50 disabled:cursor-not-allowed rounded-lg transition-all duration-200"
              :class="newGame ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700'"
            >
              <Play v-if="newGame" class="w-5 h-5 mr-2" />
              <Gamepad2 v-else class="w-5 h-5 mr-2" />
              {{ newGame ? 'Iniciar Nuevo Juego' : 'Continuar Serie' }}
            </button>
            
            <button 
              type="button"
              @click="clearForm"
              class="btn bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500 
                px-6 py-3 rounded-lg"
            >
              Limpiar
            </button>
          </div>
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

/* Dropdown styles */
.players-dropdown {
  border-top: none;
}

.players-dropdown::-webkit-scrollbar {
  width: 6px;
}

.players-dropdown::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.players-dropdown::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.players-dropdown::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>