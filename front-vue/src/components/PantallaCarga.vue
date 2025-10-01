<template>
  <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-500/50">
    <div class="text-center text-white">
      <!-- Controlador de juego -->
      <div class="game-controller mb-8">
        <div class="relative mx-auto h-[100px] w-[200px] rounded-[50px] bg-gray-800 shadow-[0_10px_30px_rgba(0,0,0,0.3)]">
          <!-- D-Pad -->
          <div class="absolute left-[30px] top-1/2 h-[50px] w-[50px] -translate-y-1/2">
            <div class="absolute left-[17.5px] top-0 h-[20px] w-[15px] rounded-sm bg-gray-600"></div>
            <div class="absolute right-0 top-[17.5px] h-[15px] w-[20px] rounded-sm bg-gray-600"></div>
            <div class="absolute bottom-0 left-[17.5px] h-[20px] w-[15px] rounded-sm bg-gray-600"></div>
            <div class="absolute left-0 top-[17.5px] h-[15px] w-[20px] rounded-sm bg-gray-600"></div>
            <div class="absolute left-[17.5px] top-[17.5px] h-[15px] w-[15px] rounded-full bg-gray-900"></div>
          </div>
          
          <!-- Botones -->
          <div class="absolute right-[30px] top-1/2 h-[50px] w-[50px] -translate-y-1/2">
            <div class="btn btn-a absolute right-[5px] top-[25px] h-[18px] w-[18px] rounded-full bg-green-500 opacity-80"></div>
            <div class="btn btn-b absolute right-[25px] top-[5px] h-[18px] w-[18px] rounded-full bg-red-500 opacity-80"></div>
            <div class="btn btn-x absolute right-[25px] top-[25px] h-[18px] w-[18px] rounded-full bg-blue-500 opacity-80"></div>
            <div class="btn btn-y absolute right-[5px] top-[5px] h-[18px] w-[18px] rounded-full bg-yellow-400 opacity-80"></div>
          </div>
        </div>
      </div>
      
      <!-- Texto -->
      <h2 class="mb-4 text-3xl font-bold drop-shadow-[2px_2px_4px_rgba(0,0,0,0.3)]">
        {{ loadingText }}
      </h2>
      
      <!-- Puntos animados -->
      <div class="dots flex justify-center gap-2.5">
        <span class="inline-block h-3 w-3 rounded-full bg-white"></span>
        <span class="inline-block h-3 w-3 rounded-full bg-white"></span>
        <span class="inline-block h-3 w-3 rounded-full bg-white"></span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  loadingText?: string
}

withDefaults(defineProps<Props>(), {
  loadingText: 'Cargando juegos'
})
</script>

<style scoped>
@keyframes float {
  0%, 100% { 
    transform: translateY(0px); 
  }
  50% { 
    transform: translateY(-20px); 
  }
}

@keyframes pulse {
  0%, 100% { 
    transform: scale(1); 
    opacity: 0.8; 
  }
  50% { 
    transform: scale(1.2); 
    opacity: 1; 
  }
}

@keyframes bounce {
  0%, 80%, 100% { 
    transform: scale(0); 
    opacity: 0.5; 
  }
  40% { 
    transform: scale(1); 
    opacity: 1; 
  }
}

.game-controller {
  animation: float 3s ease-in-out infinite;
}

.btn {
  animation: pulse 1.5s ease-in-out infinite;
}

.btn-a {
  animation-delay: 0s;
}

.btn-b {
  animation-delay: 0.2s;
}

.btn-x {
  animation-delay: 0.4s;
}

.btn-y {
  animation-delay: 0.6s;
}

.dots span {
  animation: bounce 1.4s infinite ease-in-out;
}

.dots span:nth-child(1) {
  animation-delay: -0.32s;
}

.dots span:nth-child(2) {
  animation-delay: -0.16s;
}
</style>