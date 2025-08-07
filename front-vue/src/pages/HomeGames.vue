<script setup lang="ts">
import Header from '@/components/Header.vue';
import { ArrowLeft } from 'lucide-vue-next';


</script>

<template>
    <Header 
      :titulo="'Manager Games'" 
      :descrip="'Lleva el control de tus juegos y mantén un registro de ganancias'">
      <template #boton_return>
        <button class="p-2 boton_retorno"><ArrowLeft/></button>
      </template>
      <template #botones>
        
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