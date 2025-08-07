<script setup lang="ts">
    import Header from '@/components/Header.vue';
import { ArrowLeft, ChartColumn, History } from 'lucide-vue-next';
</script>

<template>
    <Header 
      :titulo="'Game Tracker'" 
      :descrip="'Lleva el control de tus juegos y mantén un registro de ganancias'">
      <template #boton_return>
        <button class="p-2 boton_retorno"><ArrowLeft/></button>
      </template>
      <template #botones>
        <a href="#" class="flex p-2 gap-2 boton_header"> <History />Estadistica</a>
        <a class="flex p-2 gap-2 boton_header"> <ChartColumn />Historial</a>
      </template>
    </Header>


    
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
</style>