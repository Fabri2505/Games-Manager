<script setup lang="ts">
  import { ref } from 'vue'

  // Variables reactivas
  const form = ref({
    nombre: '',
    apellido: '',
    email: '',
    password: ''
  })

  // Función para manejar el envío
  const handleSubmit = (event:Event) => {
    event.preventDefault()
    console.log('Datos del formulario:', form.value)
    
    // Aquí irían las validaciones y envío al backend
    alert(`Registro enviado:
    Nombre: ${form.value.nombre}
    Apellido: ${form.value.apellido}
    Email: ${form.value.email}
    Password: ${form.value.password}`)
  }

</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-purple-50 flex items-center justify-center p-4">
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 w-full max-w-md">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">🎰 Casino Games</h1>
        <p class="text-gray-600">Crear nueva cuenta</p>
      </div>

      <form @submit="handleSubmit" class="space-y-6">
        <!-- Nombre -->
        <div class="form-group">
          <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">
            Nombre
          </label>
          <input 
            id="nom" 
            v-model="form.nombre"
            type="text" 
            required
            placeholder="Ingresa tu nombre"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors"
          >
        </div>

        <!-- Apellido -->
        <div class="form-group">
          <label for="ape" class="block text-sm font-semibold text-gray-700 mb-2">
            Apellido
          </label>
          <input 
            id="ape" 
            v-model="form.apellido"
            type="text" 
            required
            placeholder="Ingresa tu apellido"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors"
          >
        </div>

        <!-- Email -->
        <div class="form-group">
          <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
            Email
          </label>
          <input 
            id="email" 
            v-model="form.email"
            type="email" 
            required
            placeholder="tu@email.com"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors"
          >
        </div>

        <!-- Password -->
        <div class="form-group">
          <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
            Contraseña
          </label>
          <input 
            id="password" 
            v-model="form.password"
            type="password" 
            required
            minlength="8"
            placeholder="Mínimo 8 caracteres"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors"
          >
        </div>

        <!-- Botón -->
        <button 
          type="submit"
          class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-blue-700 transform hover:scale-[1.02] transition-all duration-200 shadow-lg"
        >
          🎯 Crear Cuenta
        </button>
      </form>

      <!-- Link para login -->
      <div class="text-center mt-6">
        <p class="text-gray-600">
          ¿Ya tienes cuenta? 
          <a href="#" class="text-purple-600 font-semibold hover:underline">
            Inicia sesión
          </a>
        </p>
      </div>
    </div>
  </div>
</template>