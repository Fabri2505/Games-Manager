<script setup lang="ts">
import Header from '@/components/HeaderComponent.vue';
import BotonSwitch from '@/components/HomeGolpeadoP/BotonSwitch.vue';
import { ArrowLeft } from 'lucide-vue-next';

</script>

<template>
    <Header 
      :titulo="'Configurar Juego'"
      :descrip="'Inicia un nuevo juego o continúa una serie existente'" >
      <template #boton_return>
        <button class="p-2 boton_retorno"><ArrowLeft/></button>
      </template>
      <template #botones>
        
      </template>
    </Header>

    <BotonSwitch></BotonSwitch>
    
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