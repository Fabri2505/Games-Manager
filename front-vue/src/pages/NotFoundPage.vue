<template>
  <div class="flex items-center justify-center p-4">
    
    <!-- Contenido principal -->
    <div class="text-center max-w-lg mx-auto">
      <!-- Código de error -->
      <div class="mb-8">
        <h1 class="text-6xl md:text-7xl font-bold text-red-600 mb-4">
          404
        </h1>
        <div class="text-xl md:text-2xl font-medium text-red-500 mb-2">
          Página no encontrada
        </div>
        <div class="text-gray-500 mb-8">
          La página que buscas no está disponible
        </div>
      </div>

      <!-- Imagen de cartas -->
      <div class="mb-8 flex justify-center">
        <img 
          src="/CARTAS-NOT-FOUND.png" 
          alt="Cartas no encontradas" 
          class="max-w-sm w-full h-auto"
        />
      </div>

      <!-- Mensaje simple -->
      <div class="bg-white rounded-lg p-6 mb-8 shadow-sm border border-gray-200">
        <p class="text-gray-600 mb-2">
          Lo sentimos, esta página no está disponible
        </p>
        <p class="text-gray-500 text-sm">
          Puedes regresar al inicio o usar el botón atrás
        </p>
      </div>

      <!-- Botones de acción -->
      <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <button
          @click="goHome"
          class="simple-button primary"
        >
          Volver al inicio
        </button>
        
        <button
          @click="goBack"
          class="simple-button secondary"
        >
          Página anterior
        </button>
      </div>

    </div>
  </div>
</template>

<script lang="ts">
export default {
  name: 'NotFound',
  methods: {
    goHome() {
      this.$router.push('/');
    },
    goBack() {
      this.$router.go(-1);
    }
  }
}
</script>

<style scoped>
/* Botones simples */
.simple-button {
  padding: 10px 24px;
  border-radius: 6px;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.2s ease;
  border: 1px solid transparent;
  cursor: pointer;
  font-size: 14px;
}

.simple-button.primary {
  background-color: #374151;
  color: white;
  border-color: #374151;
}

.simple-button.primary:hover {
  background-color: #1f2937;
  border-color: #1f2937;
}

.simple-button.secondary {
  background-color: transparent;
  color: #6b7280;
  border-color: #d1d5db;
}

.simple-button.secondary:hover {
  background-color: #f9fafb;
  color: #374151;
}

/* Responsive */
@media (max-width: 640px) {
  .simple-button {
    width: 100%;
  }
}
</style>