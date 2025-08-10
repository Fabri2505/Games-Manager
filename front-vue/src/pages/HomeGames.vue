<script setup lang="ts">
import Header from '@/components/HeaderComponent.vue';
import CardGame from '@/components/HomeGamesP/CardGame.vue';
import { ArrowLeft } from 'lucide-vue-next';
import { useRouter } from 'vue-router';

const router = useRouter();

const tomarRuta = (ruta:string)=>{
  router.push(ruta);
};

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
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
      <CardGame
        title="Juegos de Cartas"
        description="Registra las ganancias y pérdidas en juegos de cartas como Poker, Truco, etc."
        route="/home-golpeado"
        :is-popular="true"
        color="blue"
        icon="wallet"
        @navigate="tomarRuta"
      />
      <CardGame
        title="Juegos de Cartas"
        description="Registra las ganancias y pérdidas en juegos de cartas como Poker, Truco, etc."
        route="/golpeado"
        :is-popular="true"
        color="blue"
        icon="wallet"
        @navigate="tomarRuta"
      />
    </div>
    

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