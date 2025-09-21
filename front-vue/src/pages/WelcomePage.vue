<template>
  <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
      <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-gray-800 rounded flex items-center justify-center">
            <span class="text-white font-bold text-sm">♠</span>
          </div>
          <h1 class="text-xl font-semibold text-gray-800">GameHub</h1>
        </div>
        <nav class="hidden md:flex space-x-6">
          <a href="#juegos" class="text-gray-600 hover:text-gray-800 transition-colors">Juegos</a>
          <a href="#torneos" class="text-gray-600 hover:text-gray-800 transition-colors">Torneos</a>
          <a href="#ranking" class="text-gray-600 hover:text-gray-800 transition-colors">Ranking</a>
        </nav>
        <div class="flex space-x-3">
          <button @click="login" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
            Iniciar Sesión
          </button>
          <button @click="register" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition-colors">
            Registrarse
          </button>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="py-20 px-4">
      <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
          Bienvenido a GameHub
        </h2>
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
          La plataforma definitiva para juegos de cartas y azar. 
          Compite con jugadores de todo el mundo y demuestra tu habilidad.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button @click="startPlaying" class="px-8 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors text-lg font-medium">
            Comenzar a Jugar
          </button>
          <button @click="watchDemo" class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-lg font-medium">
            Ver Demo
          </button>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 px-4 bg-white">
      <div class="max-w-6xl mx-auto">
        <h3 class="text-3xl font-bold text-center text-gray-800 mb-12">
          ¿Por qué elegir GameHub?
        </h3>
        <div class="grid md:grid-cols-3 gap-8">
          <div class="text-center p-6">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">🎮</span>
            </div>
            <h4 class="text-xl font-semibold text-gray-800 mb-3">Variedad de Juegos</h4>
            <p class="text-gray-600">
              Desde poker clásico hasta juegos innovadores. 
              Encuentra tu juego favorito entre nuestra amplia selección.
            </p>
          </div>
          <div class="text-center p-6">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">🏆</span>
            </div>
            <h4 class="text-xl font-semibold text-gray-800 mb-3">Torneos Emocionantes</h4>
            <p class="text-gray-600">
              Participa en torneos diarios y semanales. 
              Compite por premios increíbles y reconocimiento.
            </p>
          </div>
          <div class="text-center p-6">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">👥</span>
            </div>
            <h4 class="text-xl font-semibold text-gray-800 mb-3">Comunidad Activa</h4>
            <p class="text-gray-600">
              Únete a miles de jugadores apasionados. 
              Haz amigos y mejora tus habilidades juntos.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Games Preview -->
    <section class="py-16 px-4">
      <div class="max-w-6xl mx-auto">
        <h3 class="text-3xl font-bold text-center text-gray-800 mb-12">
          Juegos Populares
        </h3>
        <div class="grid md:grid-cols-4 gap-6">
          <div v-for="game in popularGames" :key="game.id" class="game-card">
            <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow cursor-pointer">
              <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                <span class="text-xl">{{ game.icon }}</span>
              </div>
              <h4 class="font-semibold text-gray-800 mb-2">{{ game.name }}</h4>
              <p class="text-sm text-gray-600 mb-3">{{ game.description }}</p>
              <div class="flex justify-between items-center text-sm text-gray-500">
                <span>{{ game.players }} jugadores</span>
                <span class="text-green-600">Online</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 px-4 bg-gray-800 text-white">
      <div class="max-w-4xl mx-auto text-center">
        <h3 class="text-3xl font-bold mb-12">GameHub en Números</h3>
        <div class="grid md:grid-cols-4 gap-8">
          <div>
            <div class="text-3xl font-bold mb-2">{{ stats.users }}</div>
            <div class="text-gray-300">Jugadores Registrados</div>
          </div>
          <div>
            <div class="text-3xl font-bold mb-2">{{ stats.games }}</div>
            <div class="text-gray-300">Partidas Jugadas</div>
          </div>
          <div>
            <div class="text-3xl font-bold mb-2">{{ stats.tournaments }}</div>
            <div class="text-gray-300">Torneos Completados</div>
          </div>
          <div>
            <div class="text-3xl font-bold mb-2">{{ stats.online }}</div>
            <div class="text-gray-300">Jugadores Online</div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 bg-white">
      <div class="max-w-4xl mx-auto text-center">
        <h3 class="text-3xl font-bold text-gray-800 mb-6">
          ¿Listo para la Acción?
        </h3>
        <p class="text-xl text-gray-600 mb-8">
          Únete a GameHub hoy y comienza tu aventura en el mundo de los juegos de cartas.
        </p>
        <button @click="register" class="px-8 py-4 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors text-lg font-medium">
          Crear Cuenta Gratis
        </button>
        <p class="text-sm text-gray-500 mt-4">
          Sin costo inicial • Registro en 2 minutos • Soporte 24/7
        </p>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 px-4">
      <div class="max-w-6xl mx-auto">
        <div class="grid md:grid-cols-4 gap-8">
          <div>
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-8 h-8 bg-white rounded flex items-center justify-center">
                <span class="text-gray-900 font-bold text-sm">♠</span>
              </div>
              <span class="text-xl font-semibold">GameHub</span>
            </div>
            <p class="text-gray-400 text-sm">
              La mejor plataforma para juegos de cartas en línea.
            </p>
          </div>
          <div>
            <h5 class="font-semibold mb-4">Juegos</h5>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a href="#" class="hover:text-white transition-colors">Poker</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Blackjack</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Baccarat</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Rummy</a></li>
            </ul>
          </div>
          <div>
            <h5 class="font-semibold mb-4">Soporte</h5>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a href="#" class="hover:text-white transition-colors">Centro de Ayuda</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Contacto</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Reglas del Juego</a></li>
              <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
            </ul>
          </div>
          <div>
            <h5 class="font-semibold mb-4">Legal</h5>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a href="#" class="hover:text-white transition-colors">Términos de Uso</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Privacidad</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Juego Responsable</a></li>
            </ul>
          </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
          <p>&copy; 2025 GameHub. Todos los derechos reservados.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Datos reactivos
const popularGames = ref([
  {
    id: 1,
    name: 'Texas Hold\'em',
    description: 'El poker más popular del mundo',
    icon: '♠',
    players: '1.2k'
  },
  {
    id: 2,
    name: 'Blackjack',
    description: 'Llega lo más cerca posible a 21',
    icon: '♣',
    players: '890'
  },
  {
    id: 3,
    name: 'Baccarat',
    description: 'El juego favorito de los VIP',
    icon: '♦',
    players: '456'
  },
  {
    id: 4,
    name: 'Rummy',
    description: 'Forma secuencias y conjuntos',
    icon: '♥',
    players: '723'
  }
])

const stats = ref({
  users: '50K+',
  games: '2.5M+',
  tournaments: '15K+',
  online: '3.2K'
})

// Métodos
const login = (): void => {
  router.push('/login')
}

const register = (): void => {
  router.push('/register')
}

const startPlaying = (): void => {
  router.push('/home-games')
}

const watchDemo = (): void => {
  // Abrir modal de demo o video
  console.log('Mostrando demo...')
}

onMounted(() => {
  // Animaciones de entrada o carga de datos inicial
  console.log('Página de bienvenida cargada')
})
</script>

<style scoped>
.game-card:hover {
  transform: translateY(-2px);
  transition: transform 0.2s ease;
}

/* Animaciones suaves */
section {
  animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive */
@media (max-width: 768px) {
  .grid {
    grid-template-columns: 1fr;
  }
  
  h2 {
    font-size: 2.5rem;
  }
}
</style>