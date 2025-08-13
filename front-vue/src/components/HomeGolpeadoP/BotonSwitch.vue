<script setup lang="ts">
import { ref } from 'vue';
import { Link, Plus } from 'lucide-vue-next';

const newGame = ref<boolean>(true);

const emit = defineEmits<{
  gameTypeChanged: [isNewGame: boolean];
}>();

const handleNewGame = () => {
  newGame.value = true;
  emit('gameTypeChanged', true);
};

const handleContinueSeries = () => {
  newGame.value = false;
  emit('gameTypeChanged', false);
};
</script>

<template>
  <div class="flex justify-center w-full rounded-md p-1 bg-[#E3E3E3]">
      <button 
        @click="handleNewGame"
        :class="[
          'flex w-full rounded-bl-sm rounded-tl-sm gap-2 justify-center py-1 transition-colors',
          newGame ? 'bg-white' : 'bg-[#E3E3E3]'
        ]"
      > 
        <Plus/> Nuevo Juego
      </button>
      <button 
        @click="handleContinueSeries"
        :class="[
          'flex w-full gap-2 rounded-br-sm rounded-tr-sm justify-center py-1 transition-colors',
          !newGame ? 'bg-white' : 'bg-[#E3E3E3]'
        ]"
      > 
        <Link/> Continuar Serie
      </button>
  </div>
</template>

<style scoped></style>
